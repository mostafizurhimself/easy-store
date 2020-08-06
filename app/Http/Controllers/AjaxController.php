<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    /**
     * Get the location wise departments
     *
     * @param \App\Models\Location
     * @return array
     */
    public function departmentsViaLocation(Location $location)
    {
        return $location->departments->map(function($department) {
            return [ 'value' => $department->id, 'display' => $department->name ];
        });
    }

    /**
     * Get the location wise floors
     *
     * @param \App\Models\Location
     * @return array
     */
    public function floorsViaLocation(Location $location)
    {
        return $location->floors->map(function($floor) {
            return [ 'value' => $floor->id, 'display' => $floor->name ];
        });
    }

    /**
     * Get the department wise sections
     *
     * @param \App\Models\Department
     * @return array
     */
    public function sectionsViaDepartment(Department $department)
    {
        return $department->sections->map(function($section) {
            return [ 'value' => $section->id, 'display' => $section->name ];
        });
    }

    /**
     * Get the location wise designations
     *
     * @param \App\Models\Location
     * @return array
     */
    public function designationsViaLocation(Location $location)
    {
        return $location->designations->map(function($value) {
            $designation = Designation::find($value->id);
            $department = $designation->department ? $designation->department->name." >> " : "";
            $section = $designation->section ? $designation->section->name." >> " : "";
            return [ 'value' => $designation->id, 'display' => $department . $section . $designation->name ];
        });
    }

    /**
     * Get the location wise employees
     *
     * @param \App\Models\Location
     * @return array
     */
    public function employeesViaLocation(Location $location)
    {
        return $location->employees->map(function($employee) {
            return [ 'value' => $employee->id, 'display' => $employee->readableId ];
        });
    }

    /**
     * Get the location wise fabric categories
     *
     * @param \App\Models\Location
     * @return array
     */
    public function fabricCategoriesViaLocation(Location $location)
    {
        return $location->fabricCategories->map(function($category) {
            return [ 'value' => $category->id, 'display' => $category->name ];
        });
    }

    /**
     * Get the location wise fabrics
     *
     * @param \App\Models\Location
     * @return array
     */
    public function fabricsViaLocation(Location $location)
    {
        return $location->fabrics->map(function($fabric) {
            return [ 'value' => $fabric->id, 'display' => $fabric->code ];
        });
    }

    /**
     * Get the location wise material categories
     *
     * @param \App\Models\Location
     * @return array
     */
    public function materialCategoiesViaLocation(Location $location)
    {
        return $location->materialCategories->map(function($category) {
            return [ 'value' => $category->id, 'display' => $category->name ];
        });
    }

    /**
     * Get the location wise materials
     *
     * @param \App\Models\Location
     * @return array
     */
    public function materialsViaLocation(Location $location)
    {
        return $location->materials->map(function($material) {
            return [ 'value' => $material->id, 'display' => $material->code ];
        });
    }

    /**
     * Get the location wise asset categories
     *
     * @param \App\Models\Location
     * @return array
     */
    public function assetCategoriesViaLocation(Location $location)
    {
        return $location->assetCategories->map(function($category) {
            return [ 'value' => $category->id, 'display' => $category->name ];
        });
    }

    /**
     * Get the location wise assets
     *
     * @param \App\Models\Location
     * @return array
     */
    public function assetsViaLocation(Location $location)
    {
        return $location->assets->map(function($asset) {
            return [ 'value' => $asset->id, 'display' => $asset->code ];
        });
    }
}
