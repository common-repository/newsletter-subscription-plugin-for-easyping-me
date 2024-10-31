<?php
/**
 * Setup menus in WP admin.
 *
 * @package easyping\Admin
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'EPME_Admin_Menus', false ) ) {
	return new EPME_Admin_Menus();
}

/**
 * EPME_Admin_Menus Class.
 */
class EPME_Admin_Menus {

	/**
	 * Name of admin page.
	 *
	 * @var $page
	 */
	private static $page;

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		// Add menus.
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );
		add_action( 'admin_menu', array( $this, 'dashboard_menu' ), 20 );
		add_action( 'admin_menu', array( $this, 'platform_menu' ), 30 );
		add_action( 'admin_menu', array( $this, 'channels_menu' ), 40 );
		add_action( 'admin_menu', array( $this, 'widgets_menu' ), 50 );
		add_action( 'admin_menu', array( $this, 'subscribers_menu' ), 50 );
		add_action( 'admin_menu', array( $this, 'campaigns_menu' ), 60 );
	}

	/**
	 * Return name of admin page.
	 *
	 * @return string
	 */
	public static function get_admin_page_name() {
		return self::$page;
	}

	/**
	 * Add menu items.
	 */
	public function admin_menu() {
		if ( current_user_can( 'manage_options' ) ) {
			add_menu_page( __( 'easyping.me', 'easyping.me' ), __( 'easyping.me', 'easyping.me' ), 'manage_options', 'easyping', null, 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgICB4bWxuczpzb2RpcG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaXBvZGktMC5kdGQiICAgeG1sbnM6aW5rc2NhcGU9Imh0dHA6Ly93d3cuaW5rc2NhcGUub3JnL25hbWVzcGFjZXMvaW5rc2NhcGUiICAgaWQ9InN2ZzgiICAgdmVyc2lvbj0iMS4xIiAgIHZpZXdCb3g9IjAgMCA0OS4zODE4ODkgNDQuNzkzOTMxIiAgIGhlaWdodD0iNDQuNzkzOTNtbSIgICB3aWR0aD0iNDkuMzgxODg5bW0iICAgc29kaXBvZGk6ZG9jbmFtZT0iZWFzeXBpbmctbm8tdGV4dC1uby1lcC1sb2dvX2JsYWNrX2NhbnZhc19jcm9wcGVkLnN2ZyIgICBpbmtzY2FwZTp2ZXJzaW9uPSIwLjkyLjIgKDVjM2U4MGQsIDIwMTctMDgtMDYpIj4gIDxzb2RpcG9kaTpuYW1lZHZpZXcgICAgIHBhZ2Vjb2xvcj0iI2ZmZmZmZiIgICAgIGJvcmRlcmNvbG9yPSIjNjY2NjY2IiAgICAgYm9yZGVyb3BhY2l0eT0iMSIgICAgIG9iamVjdHRvbGVyYW5jZT0iMTAiICAgICBncmlkdG9sZXJhbmNlPSIxMCIgICAgIGd1aWRldG9sZXJhbmNlPSIxMCIgICAgIGlua3NjYXBlOnBhZ2VvcGFjaXR5PSIwIiAgICAgaW5rc2NhcGU6cGFnZXNoYWRvdz0iMiIgICAgIGlua3NjYXBlOndpbmRvdy13aWR0aD0iMjE2MCIgICAgIGlua3NjYXBlOndpbmRvdy1oZWlnaHQ9IjEzNTAiICAgICBpZD0ibmFtZWR2aWV3MTUiICAgICBzaG93Z3JpZD0iZmFsc2UiICAgICBpbmtzY2FwZTp6b29tPSIyLjY4ODUzMzUiICAgICBpbmtzY2FwZTpjeD0iMjI4LjMwNDkzIiAgICAgaW5rc2NhcGU6Y3k9IjQ3LjM4MzgyOSIgICAgIGlua3NjYXBlOndpbmRvdy14PSItMTEiICAgICBpbmtzY2FwZTp3aW5kb3cteT0iLTExIiAgICAgaW5rc2NhcGU6d2luZG93LW1heGltaXplZD0iMSIgICAgIGlua3NjYXBlOmN1cnJlbnQtbGF5ZXI9InN2ZzgiICAgICBmaXQtbWFyZ2luLXRvcD0iMCIgICAgIGZpdC1tYXJnaW4tbGVmdD0iMCIgICAgIGZpdC1tYXJnaW4tcmlnaHQ9IjAiICAgICBmaXQtbWFyZ2luLWJvdHRvbT0iMCIgLz4gIDxkZWZzICAgICBpZD0iZGVmczIiIC8+ICA8bWV0YWRhdGEgICAgIGlkPSJtZXRhZGF0YTUiPiAgICA8cmRmOlJERj4gICAgICA8Y2M6V29yayAgICAgICAgIHJkZjphYm91dD0iIj4gICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PiAgICAgICAgPGRjOnR5cGUgICAgICAgICAgIHJkZjpyZXNvdXJjZT0iaHR0cDovL3B1cmwub3JnL2RjL2RjbWl0eXBlL1N0aWxsSW1hZ2UiIC8+ICAgICAgICA8ZGM6dGl0bGU+PC9kYzp0aXRsZT4gICAgICA8L2NjOldvcms+ICAgIDwvcmRmOlJERj4gIDwvbWV0YWRhdGE+ICA8cGF0aCAgICAgaW5rc2NhcGU6Y29ubmVjdG9yLWN1cnZhdHVyZT0iMCIgICAgIHN0eWxlPSJmaWxsOiMwMDAwMDA7c3Ryb2tlLXdpZHRoOjAuMDg0NTQyNTkiICAgICBkPSJNIDExLjQ4NzQ3Myw0NC4yNTgzNjIgQyA4LjYyMDc2ODcsNDIuNzUxMDg2IDYuNDI2ODM1OSw0MS4xMjA5NjMgNC4xNjkyNTM2LDM4LjgyMDgxMiAyLjg2MjEzNzQsMzcuNDg5MDUzIDIuMDA2MTU5OSwzNi40NjM3MzUgMS4xMTA2Mzg0LDM1LjE1NzAwNiAtMC4wMjM5MTc4MywzMy41MDE0ODYgLTAuMTc4MzgwNjEsMzMuMDQ2ODY3IDAuMTU1MTQyOTMsMzIuMzQ0Mjk0IDAuMzcwNjgxODQsMzEuODkwMzg5IDAuNzI5MzY0NzYsMzEuNjYzNjQyIDEuMjk0NTU5MSwzMS42MjQxNDEgYyAwLjY5Mjk4NDEsLTAuMDQ4MzcgMC44OTg2MzA2LDAuMTMwMDE2IDEuOTQ3MzYxMSwxLjY5MDIwNSAxLjEwMTA4MjQsMS42MzgwMjggMS45NzUwOTI1LDIuNjk2NjUgMy4yNzA3MjA1LDMuOTYxNTg4IDEuNzgxNTE2NiwxLjczOTI5NSAzLjcwNDIzNjMsMy4xMzgzNzMgNi4wOTgzNTIzLDQuNDM3NDc3IDAuNjY0NDU2LDAuMzYwNTQxIDEuMzAwMTg1LDAuNzY0OTY4IDEuNDEyNzI3LDAuODk4NzQxIDAuNTUxOTM1LDAuNjU1OTQ0IDAuMjQxNTQyLDEuNzA3NzYzIC0wLjYxMDIwMiwyLjA2Nzc0NSAtMC41MTY5NDUsMC4yMTg0NzMgLTAuODQ1Njc0LDAuMTQ2NDI1IC0xLjkyNjA0NSwtMC40MjE1MzUgeiBtIDIuMTI5MTQ0LC00LjYwODIyMSBDIDEwLjcwNjk1NiwzOC4xMzcxMzkgOC4xMDE3NTE1LDM1Ljk0NjMxMiA2LjIzMjE1OTYsMzMuNDQwMjU4IDQuOTAzMzgwNywzMS42NTkxMjkgNC42Njg3MDcsMzEuMDUwODcxIDUuMDM1NjM5OSwzMC4zMzg3NzIgNS4zMjQ1NjYsMjkuNzc4MDI2IDUuNzA1MzIxOCwyOS41NTQwMDEgNi4yOTg4OTQ1LDI5LjU5NTQ4OSBjIDAuMjYxNjkxNSwwLjAxNzI5IDAuNTc1MTA3NiwwLjA5ODI2IDAuNjk2NDYxMywwLjE3Nzc0NSAwLjEyMTM2NzksMC4wNzk0OCAwLjQ4MjEzMjQsMC41MzQzMzEgMC44MDE3NDc0LDEuMDEwNjk3IDEuODY1NDQxNywyLjc4MDQwMiA0LjExOTExNTgsNC44MTMyODYgNy4wNTAyODc4LDYuMzU5NTcgMC40OTk0OTUsMC4yNjM1MDYgMC45ODQ0MzUsMC41NzYwMzYgMS4wNzc2MjUsMC42OTQ1MTYgMC40MzE2NjIsMC41NDg3NTkgMC4zODc1MTgsMS4zMDY4NTggLTAuMTAzMDIzLDEuNzcxMzI0IC0wLjY0MTIzNywwLjYwNzAyOSAtMS4wOTk5MTYsMC42MTU0OTQgLTIuMjA1Mjk1LDAuMDQwNiB6IG0gOS4wNzcwMTksLTIuODE0ODA3IEMgMTcuMzM3NzQ0LDM2LjI3NjA1NiAxMi41NDczNjksMzMuMjcyMzg0IDEwLjA0MzIzNiwyOC45MDMyMTcgOC41MzY3MzcxLDI2LjI3NDcwNyA3Ljk5MDM5NjcsMjIuOTk2ODQgOC41NjQ4NTU3LDIwLjAzMzQyNCA5LjYzOTU0NDMsMTQuNDg5NTUxIDE0LjI3MjI4MSw5LjkyMjI3NDUgMjAuMzE4NTQ2LDguNDQ1ODM2NyAyMi43MzE5NzEsNy44NTY0OTggMjUuNTc3ODc2LDcuNzczNDM0NyAyNy45NTQzNDcsOC4yMjI5OTg0IDM2LjU2NDQ2MSw5Ljg1MTc4MiA0Mi4xNzc1NSwxNy4xOTI1MjkgNDAuNjkzNDQ0LDI0Ljg4MzAzOCBjIC0wLjMzODk2NCwxLjc1NjQ4OCAtMS4yMjEwMjksMy43OTQwMzYgLTIuMjgxNjQsNS4yNzA0OTYgbCAtMC41MDUwOSwwLjcwMzEzNiAwLjI0Mzc0NywwLjM1MzYzMSBjIDAuMTM0MTEzLDAuMTk0NTg3IDAuNDY3MDMzLDAuNTU0NDggMC43Mzk5MTIsMC43OTk5MzIgMS4wNjA5MzEsMC45NTQzMDYgMS4xMjA4LDEuMDIyNDg4IDEuMjE5NDEzLDEuMzg4MDYyIDAuMjM5NDY3LDAuODg3NzQ0IC0wLjE1MDUyNiwxLjgwMTQ3NCAtMC45NDE4NjgsMi4yMDYxMTYgLTAuNDMwMDQzLDAuMjE5OTIxIC0wLjU2MjQxMSwwLjI0MjIzMSAtMS42ODgwNDcsMC4yODQ2MjUgLTAuODgyMjgzLDAuMDMzMjUgLTEuNDU3ODg0LDAuMDA4NyAtMi4wNzMwNTUsLTAuMDg0ODcgLTAuNzgyNDE3LC0wLjEyMDMwMiAtMi4yODY0MTQsLTAuNTQzODU3IC0yLjU2MDc1OSwtMC43MjEyMTMgLTAuMDc2NjgsLTAuMDQ5NjggLTAuMzczNjc0LDAuMDQ1NTcgLTAuODg3NzA2LDAuMjg0NzU5IC0yLjY5NTM1MywxLjI1MzQ2OSAtNi4xMjAyNzMsMS43OTYwNSAtOS4yNjQ3MTUsMS40Njc2OTEgeiBtIDQuNTQ4NjgsLTMuMDYyOTEzIGMgMS43MDk5MDMsLTAuMzIwNDc5IDMuMzA1OTAxLC0wLjg4NDQ0MiA0LjY5NTA2NCwtMS42NTkxMjcgMC40MTY3NTksLTAuMjMyMzg1IDAuNzg0NzA5LC0wLjQyMjUyNiAwLjgxNzY4NiwtMC40MjI1MjYgMC4wMzMwNSwwIDAuMzU5MTYsMC4xNzA4MzMgMC43MjQ4ODMsMC4zNzk4NSAwLjY2NDQ3NiwwLjM3OTU4OCAxLjM5ODc5NiwwLjY2MTYwMyAxLjk5NjQ5NSwwLjc2Njc4IGwgMC4zMTcwNjcsMC4wNTU3MSAtMC4zMzk2MzUsLTAuNTgwMDc0IGMgLTAuMzMzMjg4LC0wLjU2OTIzMyAtMC44NDUxNTksLTEuNzc5ODU0IC0wLjg0MjgyMywtMS45OTM0MjkgNy4xNmUtNCwtMC4wNTk2IDAuMjcxMzIyLC0wLjQxMjc2MiAwLjYwMTUxNywtMC43ODQ3NDggMS40NDE5NSwtMS42MjQ0NjYgMi4zNTI2OTcsLTMuNDYxNzI1IDIuNzAzMDQ0LC01LjQ1Mjk5MyAwLjE1NjM2NCwtMC44ODg5NTUgMC4xMzIzODksLTIuNzU1NDk5IC0wLjA0NjIzLC0zLjU5MzA1NSAtMC45MTIxMDYsLTQuMjc2Mjk4IC00LjIxMDI3NywtNy42MzQyNDkgLTguODgxNTQxLC05LjA0MjQ4MiAtMS41NjI1MjQsLTAuNDcxMDU2IC0yLjQ4MTU2NCwtMC41OTQ1NjggLTQuMzk2MjA3LC0wLjU5MDgzMSAtMS4zNzg2NjcsMC4wMDIxIC0xLjg3NDE0NiwwLjAzNzc5IC0yLjY4MzUzOCwwLjE5MDI2OCAtNC4wNjkyMjcsMC43NjYwNDcgLTcuNDY1NTE1LDMuMDYyNTI2IC05LjMzOTM3OSw2LjMxNTAzOSAtMC44NzM5OTEsMS41MTcwNCAtMS4zMTQ0NCwzLjE5NzIyNSAtMS4zMTg1ODgsNS4wMzAyOTUgLTAuMDAyMSwxLjIyNjQ1MiAwLjA4MzU4LDEuODM5MTU5IDAuNDE0OTAyLDIuOTQxNzA5IDAuNTE0NDY0LDEuNzEyMjE0IDEuNDM5NDQ0LDMuMjA5NTM1IDIuODYwNDQsNC42MzAyNzMgMi4xMzQ3NjEsMi4xMzQzNTEgNC45MzI5MzgsMy40NjUzNTUgOC4yNDg0OTMsMy45MjM0NyAwLjgyMDYwMSwwLjExMzM4MiAzLjYzODQ5NSwwLjA0MTQ3IDQuNDY4NDA0LC0wLjExNDAyNyB6IE0gNDIuMzAzMjQ2LDE1LjExODQ1NiBDIDQyLjEwMzY4NSwxNC45OTkyMzQgNDEuNzU1Mjg1LDE0LjU2NDU3OSA0MS4yMzMzNTEsMTMuNzgzOTU2IDM5LjQ3NzUxLDExLjE1NzgyMSAzNy4xNTMyMzksOS4wODUxNTQgMzQuNDMzNzQsNy43MjAzOTIyIDM0LjA4ODg3OCw3LjU0NzQwMTMgMzMuNzAxMjMyLDcuMzMxMTk0NCAzMy41NzIyOTgsNy4yNDAxMTc4IDMzLjI4MjM1OCw3LjAzNTM3ODMgMzMuMDAzNTYyLDYuNTA1NTM4OSAzMy4wMDM1NjIsNi4xNTk0NDYxIGMgMCwtMC42NjE1Mzg1IDAuNTYzMjk0LC0xLjMwMDY2MDUgMS4yMzUxMzUsLTEuNDAxNDA5NyAwLjM3MjE2LC0wLjA1NTcxNCAwLjQ2Mjc4MSwtMC4wMjYzNDkgMS40NTU1MzIsMC40NzQ2ODE0IDIuNzg4ODY3LDEuNDA3MTk3MSA1LjQyODA2OSwzLjYwODg0MzMgNy4yMzM5ODgsNi4wMzQ2ODYyIDEuNDE5MTIyLDEuOTA2MjIgMS42NzE3MjMsMi40OTAwNzQgMS4zNzk3MDUsMy4xODg5NzQgLTAuMjk4NzkyLDAuNzE1MTIgLTEuMzQwNDE3LDEuMDU5MTQxIC0yLjAwNDY3NiwwLjY2MjA3OCB6IG0gNC45ODY2ODgsLTIuMDEzNzk0IGMgLTAuMTYxOTc1LC0wLjA4NTUyIC0wLjQxNTQ0LC0wLjM0MjQyIC0wLjU2MzE0MSwtMC41NzA2NTggQyA0Ni41NzkwNzgsMTIuMzA1NzkgNDYuMjM2NjksMTEuNzc2NzA2IDQ1Ljk2NTkwOCwxMS4zNTgyNjIgNDUuMTkzNTU2LDEwLjE2NDc0NiA0NC4yMzMxNTUsOS4wMDM5ODk4IDQyLjk4NjkyMSw3Ljc1Nzc1NiA0MS4xNzAzNTIsNS45NDExNjQ0IDM5LjE3NDgwNyw0LjQ2MjYzMjIgMzYuODUwMjUzLDMuMjEwOTU0NyAzNS41MzE3OTgsMi41MDEwMTY3IDM1LjQwNDAyOSwyLjQxMzAwODggMzUuMTk1MjUxLDIuMDcwNjA5NCAzNC44MjU3MDMsMS40NjQ1NTM1IDM1LjEyNTA2MSwwLjUxODA2MzQxIDM1Ljc5Mzk0MywwLjE3NzQxMzM1IGMgMC41NjE5NzYsLTAuMjg2MjAzODMgMC44MjA4ODEsLTAuMjQ3MzI5MjIgMS44MTcxOTYsMC4yNzI4MTM3OSAyLjkwNzY3MSwxLjUxODAzMzk2IDUuMDI3MTQxLDMuMDc1NDM5MDYgNy4zNTk3OTEsNS40MDgwOTE0NiAxLjU4NjEyOSwxLjU4NjEyOTcgMi4zNTAyOTgsMi41MDE3MzU5IDMuNTAyNTIsNC4xOTY2NzI0IDEuMDEwNDE3LDEuNDg2MzA2IDEuMTA3OTkyLDEuODg5MDQ3IDAuNjM1Mjc3LDIuNjIxNTExIC0wLjM1ODcwNCwwLjU1NTggLTEuMjAxODA3LDAuNzU0Mjk5IC0xLjgxODc5MywwLjQyODE2IHoiICAgICBpZD0icGF0aDM3MTAiICAgICBzb2RpcG9kaTpub2RldHlwZXM9ImNjY2NjY2NjY2NjY2NjY2NjY2NjY2Njc2NjY3NjY2NjY2NjY2NjY2NjY3NjY2NzY2NjY2NjY2NjY2NjY2NjY2Njc2NjY2NjY2NzY2NjY2NzY2NjIiAgICAgaW5rc2NhcGU6ZXhwb3J0LXhkcGk9Ijk2IiAgICAgaW5rc2NhcGU6ZXhwb3J0LXlkcGk9Ijk2IiAvPjwvc3ZnPg==);', '72.2' );
		}
	}

	/**
	 * Add menu item.
	 */
	public function dashboard_menu() {
		if ( current_user_can( 'manage_options' ) ) {
			add_submenu_page( 'easyping', __( 'Dashboard', 'easyping.me' ), __( 'Dashboard', 'easyping.me' ), 'manage_options', 'epme-dashboard', array( $this, 'dashboard_page' ) );
		}
	}

	/**
	 * Add menu item.
	 */
	public function platform_menu() {
		global $submenu;

		if ( isset( $submenu['easyping'] ) ) {
			// Remove 'easyping.me' sub menu item.
			unset( $submenu['easyping'][0] );
		}

		if ( current_user_can( 'manage_options' ) ) {
			add_submenu_page( 'easyping', __( 'Platform', 'easyping.me' ), __( 'Platform', 'easyping.me' ), 'manage_options', 'epme-platform', array( $this, 'platform_page' ) );
		}
	}

	/**
	 * Add menu item.
	 */
	public function channels_menu() {
		if ( current_user_can( 'manage_options' ) AND EPME_Authorization::is_authorization() ) {
			add_submenu_page( 'easyping', __( 'Channels', 'easyping.me' ), __( 'Channels', 'easyping.me' ), 'manage_options', 'epme-channels', array( $this, 'channels_page' ) );
		} else {
			add_submenu_page( '', __( 'Channels', 'easyping.me' ), __( 'Channels', 'easyping.me' ), 'manage_options', 'epme-channels', array( $this, 'error_page' ) );
		}
	}

	/**
	 * Add menu item.
	 */
	public function widgets_menu() {
		if ( current_user_can( 'manage_options' ) AND EPME_Authorization::is_authorization() ) {
			add_submenu_page( 'easyping', __( 'Widgets', 'easyping.me' ), __( 'Widgets', 'easyping.me' ), 'manage_options', 'epme-widgets', array( $this, 'widgets_page' ) );
		} else {
			add_submenu_page( '', __( 'Widgets', 'easyping.me' ), __( 'Widgets', 'easyping.me' ), 'manage_options', 'epme-widgets', array( $this, 'error_page' ) );
		}
	}

	/**
	 * Add menu item.
	 */
	public function subscribers_menu() {
		if ( current_user_can( 'manage_options' ) AND EPME_Authorization::is_authorization() ) {
			add_submenu_page( 'easyping', __( 'Subscribers', 'easyping.me' ), __( 'Subscribers', 'easyping.me' ), 'manage_options', 'epme-subscribers', array( $this, 'subscribers_page' ) );
		} else {
			add_submenu_page( '', __( 'Subscribers', 'easyping.me' ), __( 'Subscribers', 'easyping.me' ), 'manage_options', 'epme-subscribers', array( $this, 'error_page' ) );
		}
	}

	/**
	 * Add menu item.
	 */
	public function campaigns_menu() {
		if ( current_user_can( 'manage_options' ) AND EPME_Authorization::is_authorization() ) {
			add_submenu_page( 'easyping', __( 'Campaigns', 'easyping.me' ), __( 'Campaigns', 'easyping.me' ), 'manage_options', 'epme-campaigns', array( $this, 'campaigns_page' ) );
		} else {
			add_submenu_page( '', __( 'Campaigns', 'easyping.me' ), __( 'Campaigns', 'easyping.me' ), 'manage_options', 'epme-campaigns', array( $this, 'error_page' ) );
		}
	}

	/**
	 * Init the error page.
	 */
	public function error_page() {
		self::$page = 'error';
		EPME_Admin_Error_Page::output();
	}

	/**
	 * Init the Dashboard page.
	 */
	public function dashboard_page() {
		self::$page = 'dashboard';
		EPME_Admin_Dashboard::output();
	}

	/**
	 * Init the Platform page.
	 */
	public function platform_page() {
		self::$page = 'platform';
		EPME_Admin_Platform::output();
	}

	/**
	 * Init the Channels page.
	 */
	public function channels_page() {
		self::$page = 'channels';
		EPME_Admin_Channels::output();
	}

	/**
	 * Init the Widgets page.
	 */
	public function widgets_page() {
		self::$page = 'widgets';
		EPME_Admin_Widgets::output();
	}

	/**
	 * Init the Widgets page.
	 */
	public function subscribers_page() {
		self::$page = 'subscribers';
		EPME_Admin_Subscribers::output();
	}

	/**
	 * Init the Widgets page.
	 */
	public function campaigns_page() {
		self::$page = 'campaigns';
		EPME_Admin_Campaigns::output();
	}
}

return new EPME_Admin_Menus();
