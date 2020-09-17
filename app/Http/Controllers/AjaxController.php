<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Section;
use App\Models\Location;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Enums\RequisitionStatus;

class AjaxController extends Controller
{

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
     * Get the location wise floors
     *
     * @param \App\Models\Location
     * @return array
     */
    public function stylesViaLocation(Location $location)
    {
        return $location->styles->map(function($style) {
            return [ 'value' => $style->id, 'display' => $style->code ];
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
     * Get the location wise sections
     *
     * @param \App\Models\Location
     * @return array
     */
    public function sectionsViaLocation(Location $location)
    {
        return $location->sections->map(function($section) {
            return [ 'value' => $section->id, 'display' => $section->name ];
        });
    }

    /**
     * Get the floor wise sections
     *
     * @param \App\Models\Location
     * @return array
     */
    public function sectionsViaFloor(Floor $floor)
    {
        return $floor->sections->map(function($section) {
            return [ 'value' => $section->id, 'display' => $section->name ];
        });
    }

    /**
     * Get the section wise sub_sections
     *
     * @param \App\Models\Section
     * @return array
     */
    public function subSectionsViaSection(Section $section)
    {
        return $section->subSections->map(function($subSection) {
            return [ 'value' => $subSection->id, 'display' => $subSection->name ];
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

    /**
     * Get the location wise requisitions
     *
     * @param \App\Models\Location
     * @return array
     */
    public function assetRequisitionsViaLocation(Location $location)
    {
        return $location->assetRequisitions->where('status', '!=', RequisitionStatus::DRAFT())->map(function($requisition) {
            return [ 'value' => $requisition->id, 'display' => $requisition->readableId ];
        });
    }

    /**
     * Get the location wise service categories
     *
     * @param \App\Models\Location
     * @return array
     */
    public function serviceCategoriesViaLocation(Location $location)
    {
        return $location->serviceCategories->map(function($category) {
            return [ 'value' => $category->id, 'display' => $category->name ];
        });
    }

    /**
     * Get the location wise services
     *
     * @param \App\Models\Location
     * @return array
     */
    public function servicesViaLocation(Location $location)
    {
        return $location->services->map(function($service) {
            return [ 'value' => $service->id, 'display' => $service->name ];
        });
    }

    /**
     * Get the location wise service categories
     *
     * @param \App\Models\Location
     * @return array
     */
    public function productCategoriesViaLocation(Location $location)
    {
        return $location->productCategories->map(function($category) {
            return [ 'value' => $category->id, 'display' => $category->name ];
        });
    }

    /**
     * Get the location wise products
     *
     * @param \App\Models\Location
     * @return array
     */
    public function productsViaLocation(Location $location)
    {
        return $location->products->map(function($product) {
            return [ 'value' => $product->id, 'display' => $product->name ];
        });
    }

    /**
     * Get the location wise expensers
     *
     * @param \App\Models\Location
     * @return array
     */
    public function expensersViaLocation(Location $location)
    {
        return $location->expensers->map(function($expenser) {
            return [ 'value' => $expenser->id, 'display' => $expenser->name ];
        });
    }

    /**
     * Get the location wise service categories
     *
     * @param \App\Models\Location
     * @return array
     */
    public function expenseCategoriesViaLocation(Location $location)
    {
        return $location->expenseCategories->map(function($category) {
            return [ 'value' => $category->id, 'display' => $category->name ];
        });
    }

}
