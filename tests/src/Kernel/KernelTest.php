<?php

namespace Drupal\Tests\indiveo_oembed_drupal\Kernel;

use Drupal\KernelTests\KernelTestBase;

class KernelTest extends KernelTestBase
{
    protected static $modules = [
        'system',
        'user',
        'field',
        'file',
        'image',
        'media',
        'oembed_providers',
        'indiveo_oembed_drupal_test',
        'indiveo_oembed_drupal'
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->installConfig([
            'field',
            'file',
            'system',
            'image',
            'media',
            'oembed_providers',
            'indiveo_oembed_drupal_test',
            'indiveo_oembed_drupal'
        ]);
    }

    public function testFetchResourceFromCache()
    {
        $this->container->get('cache.default')->set(
            "media:oembed_resource:https://indiveo.services/embed/f1557bd7-1584-495a-aecd-827189d6a471",
            [
                'version' => '1.0',
                'type' => 'rich',
                'title' => 'mock',
                'html' => 'mock_oembed_result',
                'width' => 800,
                'height' => 507
            ]
        );

        /** @var \Drupal\media\OEmbed\ResourceFetcher $resourceFetcher */
        $resourceFetcher = $this->container->get('media.oembed.resource_fetcher');

        /** @var \Drupal\media\OEmbed\Resource $resource */
        $resource = $resourceFetcher->fetchResource("https://indiveo.services/embed/f1557bd7-1584-495a-aecd-827189d6a471");

        $this->assertSame('mock', $resource->getTitle());
        $this->assertSame('mock_oembed_result', $resource->getHtml());
    }

    public function testItCanGetOembedEndpointByProvidingAUrl()
    {
        /** @var \Drupal\media\OEmbed\UrlResolver $urlResolver */
        $urlResolver = $this->container->get('media.oembed.url_resolver');

        $this->assertSame(
            'https://indiveo.services/oembed?url=https%3A//indiveo.services/embed/f1557bd7-1584-495a-aecd-827189d6a471',
            $urlResolver->getResourceUrl('https://indiveo.services/embed/f1557bd7-1584-495a-aecd-827189d6a471')
        );
    }

    public function testItCanGetMediaTypeDependencies()
    {
        /** @var \Drupal\media\Entity\MediaType $mediaType */
        $mediaType = $this->container->get('entity_type.manager')->getStorage('media_type')->load('indiveo');

        $this->assertSame('Indiveo', $mediaType->label());

        $this->assertSame([
            'config' => [
                'oembed_providers.bucket.indiveo',
            ],
            'module' => [
                'oembed_providers'
            ],
        ], $mediaType->getDependencies());
    }

    public function testItCanGetOEmbedProviderSettings()
    {
        /** @var \Drupal\media\OEmbed\Provider $provider */
        $provider = $this->container->get('media.oembed.provider_repository')->get('Indiveo');

        $this->assertSame('Indiveo', $provider->getName());
        $this->assertSame('https://indiveo.services', $provider->getUrl());
        $this->assertCount(1, $provider->getEndpoints());

        /** @var \Drupal\media\OEmbed\Endpoint $endpoint */
        $endpoint = $provider->getEndpoints()[0];

        $this->assertSame('https://indiveo.services/oembed', $endpoint->getUrl());
        $this->assertSame(['https://indiveo.services/embed/*', 'https://indiveo.services/divis/weblink/*'], $endpoint->getSchemes());
        $this->assertSame(['json'], $endpoint->getFormats());
        $this->assertTrue($endpoint->supportsDiscovery());
    }

    public function testItCanGetOEmbedProviderBucketFromEntityTypeManager()
    {
        /** @var \Drupal\oembed_providers\Entity\ProviderBucket $providerBucket */
        $providerBucket = $this->container->get('entity_type.manager')->getStorage('oembed_provider_bucket')->load('indiveo');

        $this->assertSame('Indiveo', $providerBucket->get('label'));
        $this->assertSame(['Indiveo'], $providerBucket->get('providers'));
    }

    public function testItCanGetOEmbedProviderBucketFromMediaSourceManager()
    {
        /** @var \Drupal\media\MediaSourceManager $mediaSourceManager */
        $mediaSourceManager = $this->container->get('plugin.manager.media.source');

        /** @var array $definitions */
        $definitions = $mediaSourceManager->getDefinitions();

        $this->assertSame([
            'id' => 'indiveo',
            'label' => 'Indiveo',
            'description' => '',
            'allowed_field_types' => [
                'string'
            ],
            'default_thumbnail_filename' => 'no-thumbnail.png',
            'providers' => [
                147 => 'Indiveo'
            ],
            'class' => 'Drupal\oembed_providers\Plugin\media\Source\OEmbed',
            'default_name_metadata_attribute' => 'default_name',
            'thumbnail_uri_metadata_attribute' => 'thumbnail_uri',
            'thumbnail_width_metadata_attribute' => 'thumbnail_width',
            'thumbnail_height_metadata_attribute' => 'thumbnail_height',
            'forms' => [
                'media_library_add' => 'Drupal\media_library\Form\OEmbedForm'
            ],
            'provider' => 'oembed_providers',
        ], $definitions['oembed:indiveo']);
    }

    public function testItCanGetFieldMediaFieldInformation()
    {
        /** @var \Drupal\field\Entity\FieldConfig $fieldCondig */
        $fieldCondig = $this->container->get('entity_type.manager')->getStorage('field_config')->load('media.indiveo.field_media_oembed_video');

        $this->assertSame('Indiveo URL', $fieldCondig->get('label'));
    }
}