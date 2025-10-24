# 🪝 WordPress Hooks - Documentazione

## 📁 Scopo di questa Cartella

La cartella `shop/Hooks/` contiene **funzioni procedurali** che devono rimanere tali perché:

1. Sono **WordPress Hooks** (action/filter) che vengono chiamati direttamente da WordPress
2. Devono avere una firma specifica che WordPress si aspetta
3. Non possono essere metodi di classe (o richiederebbero callback complessi)

## 🎯 File Presenti

### `aggregated_table_hooks.php`

**Scopo**: Mantiene sincronizzata la tabella `wp_ads_and_prods` (tabella aggregata)

**Funzionalità**:
- ✅ Sincronizzazione automatica quando vengono create/aggiornate/eliminate advertisements
- ✅ Sincronizzazione automatica quando vengono salvati/eliminati prodotti WooCommerce
- ✅ Helper functions per operazioni CRUD sulla tabella aggregata
- ✅ Utility per pulizia e sincronizzazione massiva

**Hooks WordPress Registrati**:
```php
add_action('save_post', 'custom_save_post_product');
// add_action('delete_post', 'custom_delete_action'); // Opzionale
```

## 📋 Funzioni Disponibili

### CRUD Operations

#### `insert_ad_or_product()`
```php
insert_ad_or_product(
    string|int $item_id,     // Hash per ads, ID per products
    string $type,            // 'advertisement' o 'product'
    string $name,            // Nome/titolo
    float $price,            // Prezzo
    string $date,            // Data (Y-m-d)
    string $category,        // Categoria principale
    string $sub_category     // Sottocategoria
): bool
```

#### `update_ad_or_product()`
```php
update_ad_or_product(
    string|int $item_id,     // ID dell'item
    float $price,            // Nuovo prezzo
    string|null $name        // Nuovo nome (opzionale)
): bool
```

#### `delete_ad_or_product()`
```php
delete_ad_or_product(string|int $item_id): bool
```

#### `is_ad_or_product()`
```php
is_ad_or_product(string|int $object_id): bool
```

### WordPress Hooks

#### `custom_save_post_product()`
Hook eseguito automaticamente quando un prodotto WooCommerce viene salvato.
Sincronizza i dati nella tabella aggregata.

#### `custom_delete_action()`
Hook eseguito quando un prodotto viene eliminato definitivamente.
Rimuove l'entry dalla tabella aggregata.

### Utility Functions

#### `sync_all_products_to_aggregated_table()`
```php
sync_all_products_to_aggregated_table(): array
// Returns: ['inserted' => int, 'updated' => int, 'errors' => int]
```
Sincronizza manualmente tutti i prodotti WooCommerce esistenti.
Utile per inizializzazione o reset della tabella.

#### `cleanup_orphaned_aggregated_items()`
```php
cleanup_orphaned_aggregated_items(): int
// Returns: numero di items rimossi
```
Pulisce la tabella da items orfani (products/ads che non esistono più).

## 🔄 Come Funziona la Sincronizzazione

### Flow per Products WooCommerce

```
Salvataggio Prodotto
        ↓
WordPress Hook: save_post
        ↓
custom_save_post_product()
        ↓
    Estrai dati:
    - Nome, Prezzo
    - Data creazione
    - Categorie
        ↓
    Già in tabella?
    ├─ SI → update_ad_or_product()
    └─ NO → insert_ad_or_product()
        ↓
Tabella wp_ads_and_prods aggiornata
```

### Flow per Advertisements

Gli advertisements vengono sincronizzati tramite il **Service Layer**:

```
AdvertisementService::createAdvertisement()
        ↓
    Salva in wp_advertisements
        ↓
    Chiama hooksHelper->insert_ad_or_product()
        ↓
Tabella wp_ads_and_prods aggiornata
```

## 🎨 Integrazione con Architettura OOP

Anche se questi hooks sono procedurali, si integrano perfettamente con l'architettura OOP:

### Nel AdvertisementService
```php
// bootstrap/advertisement.php
$hooksHelper = new class {
    public function insert_ad_or_product($hash, $type, $title, $price, $date, $category, $subcategory) {
        // Chiama la funzione procedurale del hook
        return insert_ad_or_product($hash, $type, $title, $price, $date, $category, $subcategory);
    }
};

$service = new AdvertisementService($repository, $validator, $userManager, $hooksHelper);
```

## ⚠️ Note Importanti

1. **Non convertire in OOP**: Questi hook devono rimanere funzioni procedurali
2. **Tabella Aggregata**: `wp_ads_and_prods` viene usata dal `MixedService` per query performanti
3. **Sincronizzazione Automatica**: I products si sincronizzano automaticamente via WordPress hooks
4. **Sincronizzazione Manuale**: Gli advertisements si sincronizzano tramite chiamate esplicite nel Service

## 🔧 Manutenzione

### Quando aggiungere nuovi hooks:
1. Creare un nuovo file in questa cartella (es. `user_hooks.php`)
2. Documentare chiaramente il comportamento
3. Registrare gli hooks con `add_action()` o `add_filter()`
4. Includerlo nel bootstrap appropriato

### Best Practices:
- ✅ Mantenere funzioni piccole e focalizzate
- ✅ Documentare parametri e return values
- ✅ Usare prepared statements per sicurezza
- ✅ Gestire errori gracefully
- ✅ Loggare operazioni critiche

## 📚 Riferimenti

- [WordPress Plugin API](https://developer.wordpress.org/plugins/hooks/)
- [WooCommerce Hooks](https://woocommerce.github.io/code-reference/hooks/hooks.html)
- Architecture: `../../../ARCHITECTURE.md`
