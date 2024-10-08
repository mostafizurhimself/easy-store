<?php

namespace Easystore\PermissionCheckbox;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class PermissionCheckbox extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'permission-checkbox';

    /**
     * Specify the available options
     *
     * @param  array  $options
     * @return self
     */
    public function options($options)
    {
        return $this->withMeta(['options' => is_array($options) ? $options : call_user_func($options)]);
    }

    /**
     * Disable type casting of array keys to numeric values to return the unmodified keys.
     */
    public function withGroups()
    {
        return $this->withMeta(['withGroups' => true]);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param  string                                  $requestAttribute
     * @param  object                                  $model
     * @param  string                                  $attribute
     * @return void
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            /**
             * When editing entries, they are returned as comma seperated string (unsure why).
             * As a result we need to include this check and explode the values if required.
             */
            if (!is_array($choices = $request[$requestAttribute])) {
                $permissions = collect(explode(',', $choices))->reject(function ($name) {
                    return empty($name);
                })->all();
            }

            $model->syncPermissions($permissions);
        }
    }
}