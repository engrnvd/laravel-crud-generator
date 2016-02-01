<?php

namespace Nvd\Crud\Commands;

use Illuminate\Console\Command;
use Nvd\Crud\Db;

class Crud extends Command
{
    public $tableName;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nvd:crud
        {tableName : The name of the table you want to generate crud for.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate crud for a specific table in the database';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->tableName = $this->argument('tableName');
        $this->generateModel();
        $this->generateRouteModelBinding();
        $this->generateRoute();
        $this->generateController();
        $this->generateViews();
    }

    public function generateRouteModelBinding()
    {
        $declaration = "\$router->model('".$this->route()."', 'App\\".$this->modelClassName()."');";
        $providerFile = app_path('Providers/RouteServiceProvider.php');
        $fileContent = file_get_contents($providerFile);

        if ( strpos( $fileContent, $declaration ) == false )
        {
            $regex = "/(public\s*function\s*boot\s*\(\s*Router\s*.router\s*\)\s*\{)/";
            if( preg_match( $regex, $fileContent ) )
            {
                $fileContent = preg_replace( $regex, "$1\n\t\t".$declaration, $fileContent );
                file_put_contents($providerFile, $fileContent);
                $this->info("Route model binding inserted successfully in ".$providerFile);
                return true;
            }

            // match was not found for some reason
            $this->warn("Could not add route model binding for the route '".$this->route()."'.");
            $this->warn("Please add the following line manually in {$providerFile}:");
            $this->warn($declaration);
            return false;
        }

        // already exists
        $this->info("Model binding for the route: '".$this->route()."' already exists.");
        $this->info("Skipping...");
        return false;
    }

    public function generateRoute()
    {
        $route = "Route::resource('{$this->route()}','{$this->controllerClassName()}');";
        $routesFile = app_path('Http/routes.php');
        $routesFileContent = file_get_contents($routesFile);

        if ( strpos( $routesFileContent, $route ) == false )
        {
            $routesFileContent = $this->getUpdatedContent($routesFileContent, $route);
            file_put_contents($routesFile,$routesFileContent);
            $this->info("created route: ".$route);

            return true;
        }

        $this->info("Route: '".$route."' already exists.");
        $this->info("Skipping...");
        return false;
    }

    protected function getUpdatedContent ( $existingContent, $route )
    {
        // check if the user has directed to add routes
        $str = "nvd-crud routes go here";
        if( strpos( $existingContent, $str ) !== false )
            return str_replace( $str, "{$str}\n\t".$route, $existingContent );

        // check for 'web' middleware group
        $regex = "/(Route\s*\:\:\s*group\s*\(\s*\[\s*\'middleware\'\s*\=\>\s*\[\s*\'web\'\s*\]\s*\]\s*\,\s*function\s*\(\s*\)\s*\{)/";
        if( preg_match( $regex, $existingContent ) )
            return preg_replace( $regex, "$1\n\t".$route, $existingContent );

        // if there is no 'web' middleware group
        return $existingContent."\n".$route;
    }

    public function generateController()
    {
        $controllerFile = $this->controllersDir().'/'.$this->controllerClassName().".php";

        if($this->confirmOverwrite($controllerFile))
        {
            $content = view($this->templatesDir().'.controller',['gen' => $this]);
            file_put_contents($controllerFile, $content);
            $this->info( $this->controllerClassName()." generated successfully." );
        }
    }

    public function generateModel()
    {
        $modelFile = $this->modelsDir().'/'.$this->modelClassName().".php";

        if($this->confirmOverwrite($modelFile))
        {
            $content = view( $this->templatesDir().'.model', [
                'gen' => $this,
                'fields' => Db::fields($this->tableName)
            ]);
            file_put_contents($modelFile, $content);
            $this->info( "Model class ".$this->modelClassName()." generated successfully." );
        }
    }

    public function generateViews()
    {
        if( !file_exists($this->viewsDir()) ) mkdir($this->viewsDir());
        foreach ( config('crud.views') as $view ){
            $viewFile = $this->viewsDir()."/".$view.".blade.php";
            if($this->confirmOverwrite($viewFile))
            {
                $content = view( $this->templatesDir().'.views.'.$view, [
                    'gen' => $this,
                    'fields' => Db::fields($this->tableName)
                ]);

                file_put_contents($viewFile, $content);
                $this->info( "View file ".$view." generated successfully." );
            }
        }
    }

    protected function confirmOverwrite($file)
    {
        // if file does not already exist, return
        if( !file_exists($file) ) return true;

        // file exists, get confirmation
        if ($this->confirm($file.' already exists! Do you wish to overwrite this file? [y|N]')) {
            $this->info("overwriting...");
            return true;
        }
        else{
            $this->info("Using existing file ...");
            return false;
        }
    }

    public function route()
    {
        return str_slug(str_replace("_"," ", str_singular($this->tableName)));
    }

    public function controllerClassName()
    {
        return studly_case(str_singular($this->tableName))."Controller";
    }

    public function viewsDir()
    {
        return base_path('resources/views/'.$this->viewsDirName());
    }

    public function viewsDirName()
    {
        return str_singular($this->tableName);
    }

    public function controllersDir()
    {
        return app_path('Http/Controllers');
    }

    public function modelsDir()
    {
        return app_path();
    }

    public function modelClassName()
    {
        return studly_case(str_singular($this->tableName));
    }

    public function modelVariableName()
    {
        return camel_case(str_singular($this->tableName));
    }

    public function titleSingular()
    {
        return ucwords(str_singular(str_replace("_", " ", $this->tableName)));
    }

    public function titlePlural()
    {
        return ucwords(str_replace("_", " ", $this->tableName));
    }

    public function templatesDir()
    {
        return config('crud.templates');
    }

}
