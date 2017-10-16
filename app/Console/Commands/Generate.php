<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Generate extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CRUD generator.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire() {

        $name = $this->ask('Class name');

        if ($this->confirm("Same table name? (${name}) [yes|no]", true)) {
            $table = $name;
        } else {
            $table = $this->ask('Table name');
        }

        if ($this->confirm("Do you wish to create the ${name} crud? [yes|no]", true)) {
            $this->info('generating crud files...');
            $this->route($name);
            $this->controller($name);
        }

        

        // // Migration
        // if ($this->confirm("Do you wish to create a migration? [yes|no]", true)) {
        //     $mig = $this->ask('Migration name');
        //     $this->migration($mig);
        // }

        exec("composer du");
    }

    private function route(string $name) {
        $class = ucfirst($name);
        $this->write(__DIR__."/../../../routes/${name}.php",
"<?php

\$app->get('${name}', [
    'as' => '${name}.index', 'uses' => '${class}Controller@index'
]);
\$app->get('${name}/{id}', [
    'as' => '${name}.show', 'uses' => '${class}Controller@show'
]);
\$app->post('${name}', [
    'as' => '${name}.create', 'uses' => '${class}Controller@create'
]);
\$app->patch('${name}/{id}', [
    'as' => '${name}.update', 'uses' => '${class}Controller@update'
]);
\$app->delete('${name}/{id}', [
    'as' => '${name}.delete', 'uses' => '${class}Controller@delete'
]);");

       exec('echo "\nrequire __DIR__.\'/'.$name.'.php\';" >> '.__DIR__.'/../../../routes/web.php');
    }

    private function controller(string $name) {
        $class = ucfirst($name);
        $this->write(__DIR__."/../../Http/Controllers/${class}Controller.php",
"<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class ${class}Controller extends BaseController
{
    public function index() {
        return response()->json(${class}::all());
    }
      
    public function show(int \$id) {
        return response()->json(${class}::find(\$id));
    }
      
    public function create(Request \$request) {
        \${name} = new ${class}();
        \${name}->name = \$request->input('name');
      
        \${name}->save();
        return response()->json(\${name}, 201);
    }
      
    public function update(Request \$request, \$id) {
        \${name} = ${class}::find(\$id);
        \${name}->name = \$request->input('name', \${name}->name);
      
        \${name}->save();
        return response()->json(\${name});
    }
    
    public function delete(\$id) {
        ${class}::find(\$id)->delete();
        return response('', 200);
    }
}");
    }

    private function migration(string $table) {
        exec("php artisan make:migration ${table}");
        $this->info('created in ./database/migrations/');
    }

    private function write(string $path, string $str) {
        $h = fopen($path, 'w');
        fwrite($h, $str);
        fclose($h);
    }
}