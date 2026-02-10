<?php

namespace Drupal\Tests\indiveo_oembed_drupal\Kernel;

use Drupal\KernelTests\KernelTestBase;

class InstallTest extends KernelTestBase
{
    protected $strictConfigSchema = false;

    protected static $modules = [
        'system',
        'user',
        'field',
        'filter',
        'file',
        'image',
        'media',
        'media_library',
        'views',
        'path',
        'editor',
        'ckeditor5',
        'oembed_providers',
        'indiveo_oembed_drupal_test'
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->installConfig([
            'field',
            'file',
            'filter',
            'system',
            'image',
            'media',
            'media_library',
            'path',
            'editor',
            'ckeditor5',
            'oembed_providers',
            'indiveo_oembed_drupal_test'
        ]);
    }

    public function testItCanInstallWithEmptyEditor()
    {
        # Arrange
        $this->container->get('config.factory')->getEditable('editor.editor.basic_html')->setData([])->save();
        $this->container->get('config.factory')->getEditable('filter.format.basic_html')->setData([])->save();

        # Act
        $this->assertSame([], $this->container->get('config.factory')->getEditable('filter.format.basic_html')->get());

        $this->container->get('module_installer')->install(['indiveo_oembed_drupal']);

        # Assert
        $this->assertSame(
            json_decode(file_get_contents(__DIR__ . '/__stubs__/none_filter_format_basic_html_settings_after_install.json'), true),
            $this->container->get('config.factory')->getEditable('filter.format.basic_html')->get()
        );
    }

    public function testItCanUninstallWithEmptyEditor()
    {
        # Arrange
        $this->container->get('config.factory')->getEditable('editor.editor.basic_html')->setData([])->save();
        $this->container->get('config.factory')->getEditable('filter.format.basic_html')->setData([])->save();

        $this->installSchema('user', ['users_data']);

        $this->container->get('module_installer')->install(['indiveo_oembed_drupal']);

        # Act
        $this->assertSame(
            json_decode(file_get_contents(__DIR__ . '/__stubs__/none_filter_format_basic_html_settings_after_install.json'), true),
            $this->container->get('config.factory')->getEditable('filter.format.basic_html')->get()
        );

        $this->container->get('module_installer')->uninstall(['indiveo_oembed_drupal']);

        # Assert
        $this->assertSame(['dependencies' => ['module' => []], 'filters' => []], $this->container->get('config.factory')->getEditable('filter.format.basic_html')->get());
    }

    public function testItCanInstall()
    {
        # Arrange
        $this->container->get('config.factory')
            ->getEditable('editor.editor.basic_html')
            ->setData(json_decode(file_get_contents(__DIR__ . '/__stubs__/setup_with_minimal_editor_editor_basic_html_settings.json'), true))
            ->save();

        $this->container->get('config.factory')
            ->getEditable('filter.format.basic_html')
            ->setData(json_decode(file_get_contents(__DIR__ . '/__stubs__/setup_with_minimal_filter_format_basic_html_settings.json'), true))
            ->save();

        # Act
        $this->assertSame(
            json_decode(file_get_contents(__DIR__ . '/__stubs__/minimal_filter_format_basic_html_settings_before_install.json'), true),
            $this->container->get('config.factory')->getEditable('filter.format.basic_html')->get()
        );

        $this->container->get('module_installer')->install(['indiveo_oembed_drupal']);

        # Assert
        $this->assertSame(
            json_decode(file_get_contents(__DIR__ . '/__stubs__/minimal_filter_format_basic_html_settings_after_install.json'), true),
            $this->container->get('config.factory')->getEditable('filter.format.basic_html')->get()
        );
    }

    public function testItCanUninstall()
    {
        # Arrange
        $this->container->get('config.factory')
            ->getEditable('editor.editor.basic_html')
            ->setData(json_decode(file_get_contents(__DIR__ . '/__stubs__/setup_with_minimal_editor_editor_basic_html_settings.json'), true))
            ->save();

        $this->container->get('config.factory')
            ->getEditable('filter.format.basic_html')
            ->setData(json_decode(file_get_contents(__DIR__ . '/__stubs__/setup_with_minimal_filter_format_basic_html_settings.json'), true))
            ->save();

        $this->installSchema('user', ['users_data']);

        $this->container->get('module_installer')->install(['indiveo_oembed_drupal']);

        # Act
        $this->assertSame(
            json_decode(file_get_contents(__DIR__ . '/__stubs__/minimal_filter_format_basic_html_settings_after_install.json'), true),
            $this->container->get('config.factory')->getEditable('filter.format.basic_html')->get()
        );

        $this->container->get('module_installer')->uninstall(['indiveo_oembed_drupal']);

        # Assert
        $this->assertSame(
            json_decode(file_get_contents(__DIR__ . '/__stubs__/minimal_filter_format_basic_html_settings_before_install.json'), true),
            $this->container->get('config.factory')->getEditable('filter.format.basic_html')->get()
        );
    }
}