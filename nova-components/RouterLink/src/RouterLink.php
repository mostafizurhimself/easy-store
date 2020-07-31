<?php

namespace Easystore\RouterLink;

use Laravel\Nova\Fields\Field;

class RouterLink extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'router-link';

    /**
     * Hide the field from resource form view
     *
     * @return void
     */
    public function __construct(string $name, ? string $attribute = null, ? mixed $resolveCallback = null)
    {
        parent::__construct($name, $attribute,  $resolveCallback);

        $this->exceptOnForms();
    }
}
