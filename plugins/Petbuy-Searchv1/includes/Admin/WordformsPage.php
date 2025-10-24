<?php
namespace Petbuy\Search\Admin;

use Petbuy\Search\Wordforms\Repository;

// --- Fallback per le costanti se non sono definite da WordPress ---
if (!defined('FS_CHMOD_DIR'))  define('FS_CHMOD_DIR', 0755);
if (!defined('FS_CHMOD_FILE')) define('FS_CHMOD_FILE', 0644);

class WordformsPage extends AbstractPage
{
    public function get_slug(): string      { return 'petbuy-search-wordforms'; }
    public function get_title(): string     { return 'Gestione Wordforms'; }
    public function get_menu_title(): string{ return 'Wordforms'; }

    /** Prova di scrittura non distruttiva nella cartella del file corrente */
    private function selftest_dir(string $targetFile, ?string &$err = null): bool {
        $dir = dirname($targetFile);
        if (!is_dir($dir)) { $err = "La cartella non esiste: $dir"; return false; }
        if (!is_writable($dir)) { $err = "La cartella non è scrivibile: $dir"; return false; }
        $tmp = @tempnam($dir, 'wf_');
        if ($tmp === false) { $err = "tempnam fallita in $dir"; return false; }
        $ok = @file_put_contents($tmp, "# selftest ".date('c')."\n");
        if ($ok === false) { @unlink($tmp); $err = "file_put_contents fallita su $tmp"; return false; }
        $ren = @rename($tmp, $tmp.'.ok');
        @unlink($tmp.'.ok');
        if (!$ren) { $err = "rename fallito in $dir (serve write sulla cartella)"; return false; }
        return true;
    }

    public function render(): void
    {
        $notice = '';
        $error  = '';

        // Path corrente del file (opzione o default in uploads)
        $default_file = trailingslashit(WP_CONTENT_DIR) . 'uploads/petbuy-search/wordforms.txt';
        $current_file = get_option(PETBUY_WORDFORMS_OPTION) ?: $default_file;

        // Repository iniziale (compat con eventuale costruttore che accetta path)
        $repo   = class_exists(Repository::class)
                ? (new Repository($current_file))
                : (new Repository());

        // Mappa canoniche=>sinonimi
        $assoc  = method_exists($repo, 'get_assoc') ? $repo->get_assoc() : [];
        $list   = array_keys($assoc);

        /* ===================== HANDLER POST ===================== */
        if (!empty($_POST['wf_action']) && check_admin_referer('wf_nonce')) {
            $action    = sanitize_text_field($_POST['wf_action']);
            $canonical = isset($_POST['canonical']) ? sanitize_text_field($_POST['canonical']) : '';
            // I sinonimi possono arrivare in un <input name="synonyms[]"> o testo singolo
            $synonymsArr = [];
            if (isset($_POST['synonyms'])) {
                $raw = $_POST['synonyms'];
                if (is_array($raw)) {
                    // array di stringhe: unisci e risplitta per virgole/spazi
                    $raw = trim(implode(' ', array_map('sanitize_text_field', $raw)));
                } else {
                    $raw = sanitize_text_field($raw);
                }
                $synonymsArr = preg_split('/[,\s]+/', trim($raw));
                $synonymsArr = array_values(array_filter(array_map('trim', $synonymsArr), fn($s)=>$s!==''));
            }

            switch ($action) {
                case 'add_canonical':
                    if ($canonical === '') { $error = 'Canonica vuota.'; break; }
                    if (method_exists($repo, 'add_canonical')) {
                        $repo->add_canonical($canonical, implode(' ', $synonymsArr));
                        $notice = 'Parola canonica aggiunta.';
                    } else {
                        $error = 'Funzione add_canonical non disponibile nel Repository.';
                    }
                    break;

                case 'add_synonyms':
                    if ($canonical === '') { $error = 'Canonica vuota.'; break; }
                    if (empty($synonymsArr)) { $error = 'Nessun sinonimo valido.'; break; }
                    if (method_exists($repo, 'add_synonyms')) {
                        $repo->add_synonyms($canonical, implode(' ', $synonymsArr));
                        $notice = 'Sinonimi aggiunti.';
                    } else {
                        $error = 'Funzione add_synonyms non disponibile nel Repository.';
                    }
                    break;

                case 'remove_synonyms':
                    if ($canonical === '') { $error = 'Canonica vuota.'; break; }
                    if (empty($_POST['synonyms'])) { $error = 'Seleziona almeno un sinonimo da rimuovere.'; break; }
                    if (method_exists($repo, 'remove_synonym')) {
                        $toRemove = is_array($_POST['synonyms']) ? $_POST['synonyms'] : [$synonymsArr];
                        foreach ($toRemove as $syn) {
                            $syn = sanitize_text_field($syn);
                            if ($syn !== '') {
                                $repo->remove_synonym($canonical, $syn);
                            }
                        }
                        $notice = 'Sinonimi rimossi.';
                    } else {
                        $error = 'Funzione remove_synonym non disponibile nel Repository.';
                    }
                    break;

                case 'remove_canonical':
                    if ($canonical === '') { $error = 'Canonica vuota.'; break; }
                    if (method_exists($repo, 'remove_canonical')) {
                        $repo->remove_canonical($canonical);
                        $notice = 'Parola canonica rimossa.';
                    } else {
                        $error = 'Funzione remove_canonical non disponibile nel Repository.';
                    }
                    break;

                case 'upload_file':
                    if (empty($_FILES['wf_file']['name'])) { $error = 'Nessun file selezionato.'; break; }

                    $filetype = wp_check_filetype($_FILES['wf_file']['name'], [ 'txt' => 'text/plain' ]);
                    if ($filetype['ext'] !== 'txt') { $error = 'Il file deve essere un .txt'; break; }

                    require_once ABSPATH . 'wp-admin/includes/file.php';
                    $uploaded = wp_handle_upload($_FILES['wf_file'], [ 'test_form' => false ]);
                    if (isset($uploaded['error'])) { $error = 'Errore upload: ' . $uploaded['error']; break; }

                    // Cartella di destinazione (in uploads)
                    $upload_dir = wp_upload_dir();
                    $dest_dir   = trailingslashit($upload_dir['basedir']) . 'petbuy-search';
                    if (!wp_mkdir_p($dest_dir)) { $error = 'Impossibile creare la cartella ' . $dest_dir; break; }
                    @chmod($dest_dir, \FS_CHMOD_DIR);

                    // Copia come 'wordforms.txt'
                    $dest_file = trailingslashit($dest_dir) . 'wordforms.txt';
                    if (!@copy($uploaded['file'], $dest_file)) { $error = 'Copia del file fallita.'; break; }
                    @chmod($dest_file, \FS_CHMOD_FILE);

                    // Aggiorna opzione + reinit repo
                    update_option(PETBUY_WORDFORMS_OPTION, $dest_file);
                    $current_file = $dest_file;
                    $repo         = new Repository($current_file);
                    $notice       = 'File caricato e impostato con successo.';
                    break;

                case 'set_path':
                    // Imposta un percorso assoluto (anche esterno a WP)
                    $manual = isset($_POST['manual_path']) ? wp_normalize_path(trim(wp_unslash($_POST['manual_path']))) : '';
                    if ($manual === '') { $error = 'Percorso vuoto.'; break; }
                    update_option(PETBUY_WORDFORMS_OPTION, $manual);
                    $current_file = $manual;
                    $repo         = new Repository($current_file);
                    $notice       = 'Percorso aggiornato: ' . esc_html($manual);
                    break;

                case 'selftest':
                    $err = null;
                    if ($this->selftest_dir($current_file, $err)) {
                        $notice = 'Test scrittura OK nella cartella del file.';
                    } else {
                        $error  = 'Test scrittura FALLITO: ' . $err;
                    }
                    break;
            }

            // Refresh mappa e lista dopo ogni azione
            if (method_exists($repo, 'get_assoc')) {
                $assoc = $repo->get_assoc(true); // forza ricarica
                $list  = array_keys($assoc);
            }
        }
        /* ================== FINE HANDLER POST =================== */

        // Stato file/cartella
        $exists   = file_exists($current_file);
        $readable = $exists && is_readable($current_file);
        $dir      = dirname($current_file);
        $dirOK    = is_dir($dir);
        $dirWr    = $dirOK && is_writable($dir);

        ?>
        <div class="wrap">
            <h1>Wordforms</h1>

            <?php if ($notice): ?>
                <div class="notice notice-success is-dismissible"><p><?= esc_html($notice); ?></p></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="notice notice-error is-dismissible"><p><?= esc_html($error); ?></p></div>
            <?php endif; ?>

            <style>
                .wf-card{background:#fff;padding:1rem;margin:1rem 0;border:1px solid #ccd0d4;border-radius:6px}
                .wf-card h2{margin-top:0}
                .wf-row{display:flex;gap:1rem;align-items:center;margin:.5rem 0}
                .wf-row label{min-width:130px}
                select[size]{height:130px}
                code.path{word-break:break-all}
            </style>

            <!-- ========= A. BOX FILE WORDFORMS ========= -->
            <div class="wf-card">
                <h2>File Wordforms</h2>
                <p>Percorso corrente: <code class="path"><?= esc_html($current_file); ?></code></p>
                <p>Stato:
                    <?= $exists ? '<span style="color:#22863a">esiste</span>' : '<span style="color:#b31d28">manca</span>' ?>
                    · <?= $readable ? 'leggibile' : 'non leggibile' ?>
                    · cartella: <?= $dirWr ? '<span style="color:#22863a">scrivibile</span>' : '<span style="color:#b31d28">non scrivibile</span>' ?>
                </p>

                <!-- Upload .txt in uploads/petbuy-search/wordforms.txt -->
                <form method="post" enctype="multipart/form-data" style="margin-top:1rem;">
                    <?php wp_nonce_field('wf_nonce'); ?>
                    <input type="hidden" name="wf_action" value="upload_file">
                    <input type="file"   name="wf_file" accept=".txt">
                    <?php submit_button('Imposta nuovo file', 'secondary', '', false); ?>
                </form>

                <!-- Imposta percorso assoluto (anche esterno) -->
                <form method="post" style="margin-top:.75rem;">
                    <?php wp_nonce_field('wf_nonce'); ?>
                    <input type="hidden" name="wf_action" value="set_path">
                    <label for="manual_path">Oppure imposta un percorso assoluto (.txt):</label><br>
                    <input type="text" id="manual_path" name="manual_path" class="regular-text code" value="<?= esc_attr($current_file); ?>">
                    <?php submit_button('Salva percorso', 'secondary', '', false); ?>
                </form>

                <!-- Self test scrittura -->
                <form method="post" style="margin-top:.75rem;">
                    <?php wp_nonce_field('wf_nonce'); ?>
                    <input type="hidden" name="wf_action" value="selftest">
                    <?php submit_button('Test scrittura cartella', 'secondary', '', false); ?>
                </form>

                <p><small>Formato riga: <code>canonica = sinonimo1 sinonimo2 ...</code></small></p>

                <?php if (strpos($current_file, WP_CONTENT_DIR) !== 0): ?>
                    <div class="notice notice-warning" style="margin-top:.75rem">
                        <p><strong>Nota:</strong> il file è fuori da WordPress. Assicurati che l'utente del webserver
                        possa attraversare tutte le cartelle madri e scrivere nella cartella del file (ACL o permessi).</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ============ B. NUOVA CANONICA ============ -->
            <div class="wf-card">
                <h2>Nuova parola canonica</h2>
                <form method="post" class="wf-form">
                    <?php wp_nonce_field('wf_nonce'); ?>
                    <input type="hidden" name="wf_action" value="add_canonical">

                    <div class="wf-row">
                        <label>Canonica</label>
                        <input type="text" name="canonical" required placeholder="es. scarpa">
                    </div>
                    <div class="wf-row">
                        <label>Sinonimi</label>
                        <input type="text" name="synonyms[]" placeholder="es. calzatura sneakers">
                    </div>

                    <?php submit_button('Aggiungi', 'primary', '', false); ?>
                </form>
            </div>

            <!-- ============ C. AGGIUNGI SINONIMI ============ -->
            <div class="wf-card">
                <h2>Aggiungi sinonimi a una canonica</h2>
                <form method="post">
                    <?php wp_nonce_field('wf_nonce'); ?>
                    <input type="hidden" name="wf_action" value="add_synonyms">

                    <div class="wf-row">
                        <label>Canonica</label>
                        <!-- popolato SOLO in PHP per evitare duplicati -->
                        <select name="canonical">
                            <?php foreach ($list as $c): ?>
                                <option value="<?= esc_attr($c); ?>"><?= esc_html($c); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="wf-row">
                        <label>Nuovi sinonimi</label>
                        <input type="text" name="synonyms[]" placeholder="separati da spazio o virgola">
                    </div>

                    <?php submit_button('Aggiungi sinonimi', 'secondary', '', false); ?>
                </form>
            </div>

            <!-- ============ D. RIMUOVI SINONIMI ============ -->
            <div class="wf-card">
                <h2>Rimuovi sinonimi</h2>
                <form method="post" id="wf-remove-syn-form">
                    <?php wp_nonce_field('wf_nonce'); ?>
                    <input type="hidden" name="wf_action" value="remove_synonyms">

                    <div class="wf-row">
                        <label>Canonica</label>
                        <!-- questo parte vuoto ed è riempito via JS -->
                        <select name="canonical" id="wf-can-rm" class="js-can-select"></select>
                    </div>
                    <div class="wf-row">
                        <label>Sinonimi</label>
                        <select name="synonyms[]" id="wf-syn-rm" multiple size="6"></select>
                    </div>

                    <?php submit_button('Rimuovi sinonimi selezionati', 'delete', '', false, [ 'onclick' => "return confirm('Procedere?');" ]); ?>
                </form>
            </div>

            <!-- ============ E. RIMUOVI CANONICA ============ -->
            <div class="wf-card">
                <h2>Elimina parola canonica</h2>
                <form method="post" onsubmit="return confirm('Eliminare la canonica e tutti i suoi sinonimi?');">
                    <?php wp_nonce_field('wf_nonce'); ?>
                    <input type="hidden" name="wf_action" value="remove_canonical">

                    <div class="wf-row">
                        <label>Canonica</label>
                        <!-- popolato in PHP; niente classe js-can-select -->
                        <select name="canonical">
                            <?php foreach ($list as $c): ?>
                                <option value="<?= esc_attr($c); ?>"><?= esc_html($c); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php submit_button('Elimina canonica', 'delete'); ?>
                </form>
            </div>

            <!-- ============ F. LISTA COMPLETA ============ -->
            <div class="wf-card">
                <h2>Lista completa</h2>
                <table class="widefat striped">
                    <thead><tr><th>Canonica</th><th>Sinonimi</th></tr></thead>
                    <tbody>
                    <?php foreach ($assoc as $can => $syns): ?>
                        <tr>
                            <td><strong><?= esc_html($can); ?></strong></td>
                            <td><?= esc_html(implode(', ', array_diff($syns, [$can]))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ======= JAVASCRIPT ======= -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const MAP = <?= wp_json_encode($assoc); ?>;

                // Popola solo i select JS che sono VUOTI (evita duplicati)
                const fillCanonicals = () => {
                    document.querySelectorAll('.js-can-select').forEach(sel => {
                        if (sel.options.length > 0) return; // già popolato lato PHP → non toccare
                        const frag = document.createDocumentFragment();
                        Object.keys(MAP).forEach(can => {
                            const opt = document.createElement('option');
                            opt.value = can;
                            opt.textContent = can;
                            frag.appendChild(opt);
                        });
                        sel.appendChild(frag);
                    });
                };

                const syncSynonymsSelect = () => {
                    const canSel = document.getElementById('wf-can-rm');
                    const synSel = document.getElementById('wf-syn-rm');
                    if (!canSel || !synSel) return;
                    const can  = canSel.value;
                    const syns = (MAP[can]||[]).filter(s => s!==can);
                    synSel.innerHTML = '';
                    syns.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s;
                        opt.textContent = s;
                        synSel.appendChild(opt);
                    });
                };

                fillCanonicals();
                syncSynonymsSelect();
                const canRm = document.getElementById('wf-can-rm');
                if (canRm) canRm.addEventListener('change', syncSynonymsSelect);
            });
        </script>
        <?php
    }
}