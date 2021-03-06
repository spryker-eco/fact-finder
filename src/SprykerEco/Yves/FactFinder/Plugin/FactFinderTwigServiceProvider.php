<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\FactFinder\Plugin;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * @method \SprykerEco\Yves\FactFinder\FactFinderFactory getFactory()
 */
class FactFinderTwigServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    use LoggerTrait;

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Twig_Environment $twig) use ($app) {
                $twig->addFunction(
                    'fact_finder_recommendations',
                    $this->createRecommendationsFunction($twig, $app)
                );

                return $twig;
            })
        );

        $app['twig'] = $app->share(
            $app->extend('twig', function (Twig_Environment $twig) use ($app) {
                $twig->addFunction(
                    'fact_finder_product_campaigns',
                    $this->createProductCampaignsFunction($twig, $app)
                );

                return $twig;
            })
        );

        $app['twig'] = $app->share(
            $app->extend('twig', function (Twig_Environment $twig) use ($app) {
                $twig->addFunction(
                    'fact_finder_shopping_cart_campaigns',
                    $this->createShoppingCartCampaignsFunction($twig, $app)
                );

                return $twig;
            })
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param \Silex\Application $app
     *
     * @return \Twig_SimpleFunction
     */
    protected function createRecommendationsFunction(Twig_Environment $twig, Application $app)
    {
        $options = ['is_safe' => ['html']];

        return new Twig_SimpleFunction('fact_finder_recommendations', function (array $params, $templatePath) use ($twig, $app) {

            $recommendationsDataProvider = $this->getFactory()
                ->createRecommendationsDataProvider();

            return $twig->render(
                $templatePath,
                [
                    'productRecommendations' => $recommendationsDataProvider->buildTemplateData($params, $app['request_stack']->getCurrentRequest()),
                    'params' => $params,
                ]
            );
        }, $options);
    }

    /**
     * @param \Twig_Environment $twig
     * @param \Silex\Application $app
     *
     * @return \Twig_SimpleFunction
     */
    protected function createProductCampaignsFunction(Twig_Environment $twig, Application $app)
    {
        $options = ['is_safe' => ['html']];

        return new Twig_SimpleFunction('fact_finder_product_campaigns', function (array $params, $templatePath) use ($twig, $app) {

            $productCampaignsDataProvider = $this->getFactory()
                ->createProductCampaignsDataProvider();

            return $twig->render(
                $templatePath,
                [
                    'campaigns' => $productCampaignsDataProvider->buildTemplateData($params, $app['request_stack']->getCurrentRequest()),
                    'params' => $params,
                ]
            );
        }, $options);
    }

    /**
     * @param \Twig_Environment $twig
     * @param \Silex\Application $app
     *
     * @return \Twig_SimpleFunction
     */
    protected function createShoppingCartCampaignsFunction(Twig_Environment $twig, Application $app)
    {
        $options = ['is_safe' => ['html']];

        return new Twig_SimpleFunction('fact_finder_shopping_cart_campaigns', function (array $params, $templatePath) use ($twig, $app) {

            $shoppingCartCampaignsDataProvider = $this->getFactory()
                ->createShoppingCartCampaignsDataProvider();

            return $twig->render(
                $templatePath,
                [
                    'campaigns' => $shoppingCartCampaignsDataProvider->buildTemplateData($params, $app['request_stack']->getCurrentRequest()),
                    'params' => $params,
                ]
            );
        }, $options);
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }
}
