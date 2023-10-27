<?php

namespace Grilar\Optimize\Providers;

use Grilar\Base\Supports\ServiceProvider;
use Grilar\Base\Traits\LoadAndPublishDataTrait;
use Grilar\Optimize\Facades\OptimizerHelper;
use Grilar\Optimize\Http\Middleware\CollapseWhitespace;
use Grilar\Optimize\Http\Middleware\DeferJavascript;
use Grilar\Optimize\Http\Middleware\ElideAttributes;
use Grilar\Optimize\Http\Middleware\InlineCss;
use Grilar\Optimize\Http\Middleware\InsertDNSPrefetch;
use Grilar\Optimize\Http\Middleware\RemoveComments;
use Grilar\Optimize\Http\Middleware\RemoveQuotes;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;

class OptimizeServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this->setNamespace('packages/optimize')
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews();

        if (! class_exists('OptimizerHelper')) {
            AliasLoader::getInstance()->alias('OptimizerHelper', OptimizerHelper::class);
        }

        $this->app['events']->listen(RouteMatched::class, function () {
            if (OptimizerHelper::isEnabled()) {
                /**
                 * @var Router $router
                 */
                $router = $this->app['router'];

                if (setting('optimize_elide_attributes', 0)) {
                    $router->pushMiddlewareToGroup('web', ElideAttributes::class);
                }

                if (setting('optimize_inline_css', 0)) {
                    $router->pushMiddlewareToGroup('web', InlineCss::class);
                }

                if (setting('optimize_insert_dns_prefetch', 0)) {
                    $router->pushMiddlewareToGroup('web', InsertDNSPrefetch::class);
                }

                if (setting('optimize_collapse_white_space', 0)) {
                    $router->pushMiddlewareToGroup('web', CollapseWhitespace::class);
                }

                if (setting('optimize_remove_comments', 0)) {
                    $router->pushMiddlewareToGroup('web', RemoveComments::class);
                }

                if (setting('optimize_remove_quotes', 0)) {
                    $router->pushMiddlewareToGroup('web', RemoveQuotes::class);
                }

                if (setting('optimize_defer_javascript', 0)) {
                    $router->pushMiddlewareToGroup('web', DeferJavascript::class);
                }
            }
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
