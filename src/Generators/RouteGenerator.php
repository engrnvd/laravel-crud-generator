<?php
/**
 * Created by naveedulhassan.
 * Date: 1/22/16
 * Time: 5:04 PM
 */

namespace Nvd\Crud\Generators;


class RouteGenerator extends Generator
{
    public function generate()
    {
        $route = "Route::resource('{$this->route()}','{$this->controller()}');";
        $routesFile = app_path('Http/routes.php');
        $routesFileContent = file_get_contents($routesFile);

        if( strpos( $routesFileContent, $route ) === -1 )
        {
            $routesFileContent .= "\n".$route;
//            $this->command->info("created route: ".$route);
        }
        else
        {
//            $this->command->info("Route: '".$route."' already exists.");
        }

        dd($routesFileContent);
    }
}