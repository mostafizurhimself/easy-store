<?php

namespace App\Nova\Actions\Expensers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\Password;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\PasswordConfirmation;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;

class AddUser extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The number of models that should be included in each chunk.
     *
     * @var int
     */
    public static $chunkCount = 200000000;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            if(!$model->user()->exists()){
                $user = User::create([
                    'name' => $model->name,
                    'location_id' => $model->locationId,
                    'email' => $fields->email,
                    'password' => $fields->password
                ]);

                // Add media
                if(!empty($fields->profile_picture)){
                    $user->addMedia($fields->profile_picture)->toMediaCollection('avatar');
                }

                // Add Role
                $user->assignRole(Role::EXPENSER);

                // Add user to the expenser
                $model->userId = $user->id;
                $model->save();

                return Action::message('User added successfully.');
            }

            return Action::danger('User added already.');
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('Email')
                ->rules('unique:users,email', 'required', 'email', 'max:50'),

            Image::make('Profile Picture', 'profile_picture') // second parameter is the media collection name
                ->required()
                ->rules('max:5000', 'mimes:jpg,jpeg,png'),

            Password::make('Password')
                ->rules('required', 'string', 'min:4', 'max:20', 'confirmed'),

            PasswordConfirmation::make('Password Confirmation')
                ->required(),
        ];
    }
}
