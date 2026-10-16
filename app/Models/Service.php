<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model implements Sortable
{
    use HasBlocks, HasTranslation, HasSlug, HasMedias, HasRevisions, HasPosition;

    /**
     * Filter groups shown on the services index (value => [en, fr] labels).
     * Service taxonomy values are stored as a flat list in the `taxonomy` json column.
     */
    public const TAXONOMY = [
        'research_phase' => [
            'discovery' => ['Discovery', 'Découverte de molécules'],
            'pre-clinical' => ['Pre-Clinical', 'Études précliniques'],
            'clinical-development-phase-i-iii' => ['Clinical development (Phase I-III)', 'Essais cliniques (Phase I-III)'],
            'commercialization' => ['Commercialization', 'Commercialisation'],
            'post-commercialization-surveillance' => ['Post-commercialization (surveillance)', 'Post-commercialisation (surveillance)'],
        ],
        'industry' => [
            'pharmaceuticals' => ['Pharmaceuticals', 'Pharmaceutiques'],
            'biologics' => ['Biologics', 'Produits biologiques'],
            'medical-devices' => ['Medical devices', 'Équipement médical'],
            'nutraceuticals-dietary-supplements' => ['Nutraceuticals & dietary supplements', 'Nutraceutiques et compléments alimentaires'],
            'cosmeceuticals' => ['Cosmeceuticals', 'Cosméceutiques'],
        ],
        'molecule_type' => [
            'small-molecules' => ['Small molecules', 'Petites molécules'],
            'biosimilar' => ['Biosimilar', 'Biosimilaire'],
            'vaccine' => ['Vaccine', 'Vaccin'],
        ],
        'location' => [
            'blainville-canada' => ['Neopharm Blainville, Canada', 'Blainville, Canada'],
            'laval-canada' => ['Neopharm Laval - Biotech City, Canada', 'Neopharm Laval, Cité Biotech, Canada'],
        ],
    ];

    protected $fillable = [
        'published',
        'position',
        'parent_id',
        'icon_svg',
        'taxonomy',
        'title',
        'hero_title',
        'hero_intro',
        'hero_button_label',
        'hero_button_url',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'taxonomy' => 'array',
    ];

    public $translatedAttributes = [
        'title',
        'hero_title',
        'hero_intro',
        'hero_button_label',
        'hero_button_url',
        'meta_title',
        'meta_description',
    ];

    public $slugAttributes = [
        'title',
    ];

    public $mediasParams = [
        'cover' => [
            'default' => [['name' => 'default', 'ratio' => 0]],
        ],
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Service::class, 'parent_id')->orderBy('position');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /** The top-level category this service belongs to (itself when top level). */
    public function category(): Service
    {
        return $this->parent_id ? ($this->parent ?? $this) : $this;
    }

    public function filterClasses(): string
    {
        $classes = $this->taxonomy ?? [];
        $classes[] = $this->category()->getSlug('en');

        return implode(' ', array_unique(array_filter($classes)));
    }
}
