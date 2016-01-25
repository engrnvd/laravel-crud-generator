<?php

namespace Nvd\Crud\Commands;

use Illuminate\Console\Command;
use Nvd\Crud\Db;
use Nvd\Crud\Generators\RouteGenerator;

class Crud extends Command
{
    public $tableName;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nvd:crud
        {tableName : The name of the table or the string "all" if you want to generate crud for all the tables at once.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate crud for a specific table or all tables in the database';

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
        $this->generateRoute();
        $this->generateController();
        $this->generateModel();
    }

    public function generateRoute()
    {
        $route = "Route::resource('{$this->route()}','{$this->controllerClassName()}');";
        $routesFile = app_path('Http/routes.php');
        $routesFileContent = file_get_contents($routesFile);

        if ( strpos( $routesFileContent, $route ) == false )
        {
            $routesFileContent .= "\n".$route;
            file_put_contents($routesFile,$routesFileContent);
            $this->info("created route: ".$route);
            return true;
        }

        $this->info("Route: '".$route."' already exists.");
        $this->info("Skipping...");
        return false;
    }

    public function generateController()
    {
        $controllerFile = $this->controllersDir().'/'.$this->controllerClassName().".php";

        if($this->confirmOverwrite($controllerFile))
        {
            $content = view('nvd::controller',['gen' => $this]);
            file_put_contents($controllerFile, $content);
            $this->info( $this->controllerClassName()." generated successfully." );
        }
    }

    public function generateModel()
    {
        $modelFile = $this->modelsDir().'/'.$this->modelClassName().".php";

        if($this->confirmOverwrite($modelFile))
        {
            $content = view( 'nvd::model', [
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
        //.....
    }

    protected function confirmOverwrite($file)
    {
        // if file does not already exist, return
        if( !file_exists($file) ) return true;

        // file exists, get confirmation
        $this->info($file.' already exists!');
        if ($this->confirm('Do you wish to overwrite this file? [y|N]')) {
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

}
