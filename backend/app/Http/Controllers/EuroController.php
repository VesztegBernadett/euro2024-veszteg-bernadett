<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EuroController extends Controller
{
    function index():View{
        $builder = DB::table('euro2024');
        $allData = $builder->orderBy("nation")
        ->paginate(6);

        $builder = DB::table('euro2024');
        $final= $builder->orderByDesc("played")->take(2)->get();

        return view("euro2024.index",[
            "title"=>"Főoldal",
            "items"=>$allData,
            "final"=>$final
        ]);
    }

    function hungary():View{
        $builder = DB::table('euro2024');
        $hungary = $builder->where("nation","=","Magyarország")->get();
        return view("euro2024.hungary",[
            "title"=>"Magyarország",
            "hungary"=>$hungary
        ]);
    }

    function nations():View{
        $builder = DB::table('euro2024');
        $contains = $builder->where("nation","like","%ország%")->get();

        $builder = DB::table('euro2024');
        $notcontains = $builder->where("nation","not like","%ország%")->get();

        return view("euro2024.nations",[
            "title"=>"Országok",
            "contains"=>$contains,
            "notContains"=>$notcontains
        ]);
    }

    function groups(string $group):View{

        $builder = DB::table('euro2024');
        $teams = $builder->where("group","=","$group")
        ->select("nation","won","drawn","lost")->get();
        return view("euro2024.groups",[
            "title"=>"Csoport $group",
            "teams"=>$teams
        ]);
    }

    function statistics():View{
        $builder = DB::table('euro2024');
        $avgGoals=$builder->average("goals");

        $builder = DB::table('euro2024');
        $minGoalsDb=$builder->min("goals");
        $minGoals=$builder->where("goals","=","$minGoalsDb")->select("nation","goals")->get();
        
        $builder = DB::table('euro2024');
        $mostGoalsFDb=$builder->where("group","=","F")->max("goals");
        $mostGoalsF=$builder->where("goals","=","$mostGoalsFDb")->select("nation")->get();

        $builder = DB::table('euro2024');
        $wonThree = $builder->where("won",">=",3)->count();

        $builder = DB::table('euro2024');
        $drawnCount = $builder->where("drawn",">",0)->count();
        return view("euro2024.statistics",[
            "title"=>"Statisztikák",
            "avgGoals"=>$avgGoals,
            "minGoals"=>$minGoals,
            "mostGoalsF"=>$mostGoalsF,
            "wonThree"=>$wonThree,
            "drawnCount"=>$drawnCount
        ]);
    }
}
