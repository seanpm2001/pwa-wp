<?php
/**
 * Tests for customizer settings.
 *
 * @package PWA
 */

use Yoast\WPTestUtils\WPIntegration\TestCase;

/**
 * Tests for class WP_Customize_Manager.
 */
class Test_WP_Customize_Manager extends TestCase {

	/**
	 * Tested instance.
	 *
	 * @var WP_Customize_Manager
	 */
	public $wp_customize;

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function setUp() {
		parent::setUp();
		$this->user_id = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $this->user_id );

		require_once ABSPATH . WPINC . '/class-wp-customize-manager.php';
		// @codingStandardsIgnoreStart
		$GLOBALS['wp_customize'] = new WP_Customize_Manager();
		// @codingStandardsIgnoreStop
		$this->wp_customize = $GLOBALS['wp_customize'];
	}

	/**
	 * Tear down.
	 */
	public function tearDown() {
		$this->wp_customize = null;
		unset( $GLOBALS['wp_customize'] );
		parent::tearDown();
	}

	public function test_pwa_customize_register_maskable_icon_setting() {
		pwa_customize_register_maskable_icon_setting( $this->wp_customize );

		$this->assertEquals( 10, has_action( 'customize_register', 'pwa_customize_register_maskable_icon_setting' ) );
		$this->assertInstanceOf( 'WP_Customize_Setting', $this->wp_customize->get_setting( 'pwa_maskable_icon' ) );
		$this->assertInstanceOf( 'WP_Customize_Control', $this->wp_customize->get_control( 'pwa_maskable_icon' ) );
	}

	public function test_pwa_maskable_icon_scripts() {
		pwa_maskable_icon_scripts();

		$this->assertEquals( 10, has_action( 'customize_controls_enqueue_scripts', 'pwa_maskable_icon_scripts' ) );
		$this->assertTrue( wp_script_is( 'customize-controls', 'enqueued' ) );
		$this->assertTrue( wp_script_is( 'pwa_customizer_script', 'enqueued' ) );
	}

}