<?php

namespace App\Category;

/**
 * CategoryRepository
 *
 * Recupera categorie e sottocategorie dalle tassonomie WordPress supportate.
 */
class CategoryRepository
{
    /**
     * @var string[] Tassonomie supportate (WooCommerce + categorie WP standard)
     */
    private array $taxonomies = [
        'product_cat',
        'category',
    ];

    /**
     * Restituisce l'albero categorie → sottocategorie con eventuale immagine.
     */
    public function getAllCategories(): array
    {
        $results = [];

        foreach ($this->taxonomies as $taxonomy) {
            $terms = \get_terms([
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
                'parent'     => 0,
            ]);

            if (\is_wp_error($terms) || empty($terms)) {
                continue;
            }

            foreach ($terms as $term) {
                if (!$term instanceof \WP_Term) {
                    continue;
                }

                $results[] = $this->mapTerm($term, $taxonomy);
            }
        }

        return $results;
    }

    private function mapTerm(\WP_Term $term, string $taxonomy): array
    {
        return [
            'taxonomy'    => $taxonomy,
            'id'          => (int) $term->term_id,
            'slug'        => $term->slug,
            'name'        => $term->name,
            'description' => $term->description,
            'permalink'   => \get_term_link($term),
            'image'       => $this->getTermImage($term),
            'children'    => $this->getChildren($term, $taxonomy),
        ];
    }

    private function getChildren(\WP_Term $parent, string $taxonomy): array
    {
        $children = \get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'parent'     => $parent->term_id,
        ]);

        if (\is_wp_error($children) || empty($children)) {
            return [];
        }

        $mapped = [];
        foreach ($children as $child) {
            if (!$child instanceof \WP_Term) {
                continue;
            }

            $mapped[] = [
                'taxonomy'    => $taxonomy,
                'id'          => (int) $child->term_id,
                'slug'        => $child->slug,
                'name'        => $child->name,
                'description' => $child->description,
                'permalink'   => \get_term_link($child),
                'image'       => $this->getTermImage($child),
                'children'    => [],
            ];
        }

        return $mapped;
    }

    private function getTermImage(\WP_Term $term): ?array
    {
        $thumbnailId = \get_term_meta($term->term_id, 'thumbnail_id', true);
        if (!$thumbnailId) {
            return null;
        }

        $thumbnailId = (int) $thumbnailId;
        $url = \wp_get_attachment_url($thumbnailId);
        if (!$url) {
            return null;
        }

        return [
            'id'     => $thumbnailId,
            'url'    => $url,
            'alt'    => \get_post_meta($thumbnailId, '_wp_attachment_image_alt', true) ?: '',
            'srcset' => \wp_get_attachment_image_srcset($thumbnailId, 'full') ?: '',
            'sizes'  => \wp_get_attachment_image_sizes($thumbnailId, 'full') ?: '',
        ];
    }
}

