<?php

namespace hafid\project_installer\http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use hafid\project_installer\lib\Helper;
use Illuminate\Support\Facades\Artisan;

class InstallController extends Controller
{
    private $minPhpVersion;
    private $extensions;

    private $permissions;


    public function index()
    {
        $this->minPhpVersion = config('project_installer.min_php_version');
        $this->extensions = config('project_installer.required_extensions');
        $this->permissions = config('project_installer.required_permissions');
        return $this->install_check();
    }

    public function install_check()
    {
        // Clear cache, routes, views
        Artisan::call('optimize:clear');

        $passed = true;

        // Permissions checker
        $results['permissions'] = [];
        foreach ($this->permissions as $folder => $permission) {
            $results['permissions'][] = [
                'folder'     => $folder,
                'permission' => substr(sprintf('%o', fileperms(base_path($folder))), -4),
                'required'   => $permission,
                'success'    => substr(sprintf('%o', fileperms(base_path($folder))), -4) >= $permission ? true : false,
            ];
        }

        // Extension checker
        $results['extensions'] = [];
        foreach ($this->extensions as $extension) {
            $results['extensions'][] = [
                'extension' => $extension,
                'success'   => extension_loaded($extension),
            ];
        }

        $results['extensions'][] = [
            'extension' => 'proc_open',
            'success'   => function_exists('proc_open'),
        ];

        // PHP version
        $results['php'] = [
            'installed' => PHP_VERSION,
            'required'  => $this->minPhpVersion,
            'success'   => version_compare(PHP_VERSION, $this->minPhpVersion) >= 0 ? true : false,
        ];


        // Pass check
        foreach ($results['permissions'] as $permission) {
            if ($permission['success'] == false) {
                $passed = false;
                break;
            }
        }

        foreach ($results['extensions'] as $extension) {
            if ($extension['success'] == false) {
                $passed = false;
                break;
            }
        }

        try {
        } catch (\Exception $e) {
        }

        if ($results['php']['success'] == false) {
            $passed = false;
        }

        return view('project_installer::install', compact(
            'results',
            'passed'
        ));
    }

    public function install_db(Request $request)
    {
        $request->validate([
            'APP_URL'     => 'required|url',
            'DB_HOST'     => 'required|string|max:50',
            'DB_PORT'     => 'required|numeric',
            'DB_DATABASE' => 'required|string|max:50',
            'DB_USERNAME' => 'required|string|max:50',
            'DB_PASSWORD' => 'nullable|string|max:50',
        ]);
        // Check DB connection
        try {

            $pdo = new \PDO(
                'mysql:host=' . $request->DB_HOST .
                    ';port=' . $request->DB_PORT .
                    ';dbname=' . $request->DB_DATABASE,
                $request->DB_USERNAME,
                $request->DB_PASSWORD,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]
            );
        } catch (\PDOException $e) {
            return redirect()->route('install.check')
                ->with('error', 'Database connection failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('install.check')
                ->with('error', 'Database error: ' . $e->getMessage());
        }

        // Setup .env file
        try {

            Helper::setEnv([
                'APP_URL'     => strtolower(rtrim($request->APP_URL, '/')),
                'APP_ENV'     => 'production',
                'APP_DEBUG'   => 'false',
                'DB_HOST'     => '"' . $request->DB_HOST . '"',
                'DB_PORT'     => '"' . $request->DB_PORT . '"',
                'DB_DATABASE' => '"' . $request->DB_DATABASE . '"',
                'DB_USERNAME' => '"' . $request->DB_USERNAME . '"',
                'DB_PASSWORD' => '"' . $request->DB_PASSWORD . '"',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('install.check')
                ->with('error', 'Can\'t save changes to .env file: ' . $e->getMessage());
        }

        // Application key
        try {
            Artisan::call('key:generate', ["--force" => true]);
        } catch (\Exception $e) {
            return redirect()->route('install.check')
                ->with('error', 'Can\'t generate application key: ' . $e->getMessage());
        }

        // Migrate
        try {
            Artisan::call('migrate', ["--force" => true]);
        } catch (\Exception $e) {
            return redirect()->route('install.check')
                ->with('error', 'Can\'t migrate database: ' . $e->getMessage());
        }
        return redirect()->route('install.administrator');
    }


    public function install_administrator()
    {
        return view('project_installer::administrator');
    }

    public function install_finish(Request $request)
    {
        $validator =  $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|same:password_confirmation',
        ]);
        // Create admin account
        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password)
        ]);

        // Save installation
        touch(storage_path('installed'));

        return redirect()->route('home')
            ->with('success', 'Installation finished successfully');
    }
}
