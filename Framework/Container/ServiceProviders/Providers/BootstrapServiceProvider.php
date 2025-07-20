<?php

declare(strict_types=1);

namespace Gemini\Container\ServiceProviders\Providers;

use Gemini\Config\Architecture\DDD\AppPath;
use Gemini\Container\Containers\Application;
use Gemini\Container\Containers\Bootstrapper;
use Gemini\Container\Containers\Kernel;
use Gemini\Container\ServiceProviders\ServiceProvider;
use Gemini\HTTP\Router\Router;
use Gemini\Logging\ErrorHandler;

/**
 * Class BootstrapServiceProvider
 *
 * This service provider is responsible for registering core services into the service container.
 * It follows the Dependency Inversion Principle, ensuring high-level modules depend on abstractions
 * rather than concrete implementations, enhancing flexibility and testability.
 */
class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Registers the necessary services into the service container.
     *
     * This method defines how services or configurations should be registered
     * within the service container, making them available for dependency injection
     * and use throughout the application.
     *
     */
    public function register() : void
    {
        // Register Bootstrapper singleton with required dependencies.
        $this->dependencyInjector->singleton(
            abstract: Bootstrapper::class,
            concrete: fn() : Bootstrapper => new Bootstrapper(
                envFilePath    : AppPath::getRoot() . 'env.php', // Dynamically resolves the env file path.
                helpersFilePath: AppPath::HELPERS_PATH->get(), // Dynamically resolves the helpers file path.
            )
        );

        // Register Application singleton with Kernel dependency.
        $this->dependencyInjector->singleton(
            abstract: Application::class,
            concrete: static fn() : Application => new Application()
        );

        // Register Kernel singleton with Router and ErrorHandler dependencies.
        $this->dependencyInjector->singleton(
            abstract: Kernel::class,
            concrete: fn() : Kernel => new Kernel(
                router      : $this->dependencyInjector->get(Router::class),
                errorHandler: $this->dependencyInjector->get(ErrorHandler::class)
            )
        );
    }

    /**
     * Starts the boot process for the class.
     * This method can be optionally overridden by derived classes to implement specific boot logic.
     *
     * @throws \Exception
     */
    public function boot() : void
    {
        /** @var Bootstrapper $bootstrapper */
        $bootstrapper = $this->dependencyInjector->get(Bootstrapper::class);
        $bootstrapper->bootstrap($this->dependencyInjector);
    }
}