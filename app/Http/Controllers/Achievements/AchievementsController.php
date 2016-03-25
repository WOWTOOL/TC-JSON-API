<?php
/**
 * Created by PhpStorm.
 * User: AvengerWeb
 * Date: 25.03.16
 * Time: 22:37
 */
namespace App\Http\Controllers\Achievements;

use App\Models\Achievements\Achievement;
use App\Models\Achievements\AchievementCategory;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

/**
 * Class AchievementsController
 * @package App\Http\Controllers\Achievements
 */
class AchievementsController extends Controller
{
    /**
     * Get achievements list or achievement by ID
     *
     * @param Request $request
     * @param int $id
     * @return Achievement|array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|static[]
     */
    public function getAchievements(Request $request, $id = 0) {

        $result = Achievement::withExtra($request->has('no_extra_fields'));

        if ($id)
            $result = $result->findOrFail($id);
        else {
            if ($category = $request->get("category"))
                $result->where('category', '=', $category);

            if ($faction = $request->get('faction'))
                switch ($faction) {
                    case "horde":
                        $result->where('Faction', '!=', '1');
                        break;
                    case "alliance":
                        $result->where('Faction', '!=', '0');
                        break;
                }

            $result = $result->get();
        }

        return $result;
    }

    /**
     * Get achievements categories list or category by ID
     *
     * @param Request $request
     * @param int $id
     * @return AchievementCategory|array
     */
    public function getAchievementCategories(Request $request, $id = 0) {

        $result = AchievementCategory::withExtra($request->has('no_extra_fields'));

        //backward compatibility
        $id = $id ?: $request->get("id");

        if ($id)
            $result = $result->findOrFail($id);
        else {
            //TODO::Search by other fields
            $result = $result->get();
        }

        return $result;
    }
}