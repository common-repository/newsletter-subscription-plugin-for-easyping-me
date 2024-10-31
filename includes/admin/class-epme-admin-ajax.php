<?php
/**
 * Admin Ajax Class.
 *
 * @package easyping\Classes
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * EPME_Admin_Ajax Class.
 */
class EPME_Admin_Ajax {

	/**
	 * Check to errors
	 *
	 * @param  string $nonce_action
	 * @param  string $nonce_field
	 * @param  string $data
	 * @param  string $function
	 * @return mixed
	 */
	public static function check_to_error( $nonce_action, $nonce_field, $data, $function ) {
		try {
            $error_core = self::check_to_error_core( $nonce_action, $nonce_field );
            if ( empty( $error_core ) ) {
                if ( $data ) {
                    switch ( $function ) {
                        case 'change_country':
                            $server_answer = EPME_Connects::change_country( $data );
                            break;
                        case 'change_name':
                            $server_answer = EPME_Connects::change_name( $data );
                            break;
                        case 'add_funds':
                            $server_answer = EPME_Connects::add_funds( $data );
                            break;
                        case 'add_redeem':
                            $server_answer = EPME_Connects::add_redeem( $data );
                            break;
                        default:
                            $answer = epme_error( __( 'Unknown function', 'easyping.me' ) );
                            echo json_encode( $answer );
                            die;
                    }
                    if ( $server_answer['type'] == 'ok' ) {
                        return $server_answer;
                    } else {
                        $answer = epme_error( $server_answer['cause'] );
                    }
                } else {
                    $answer = epme_error( __( 'Empty value', 'easyping.me' ) );
                }
            } else {
	            $answer = $error_core;
            }
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Check to core errors
	 *
	 * @param  string $nonce_action
	 * @param  string $nonce_field
	 * @return array
	 */
	public static function check_to_error_core( $nonce_action, $nonce_field ) {
		try {
			if ( !empty( $_POST ) AND wp_verify_nonce( $_POST[$nonce_field], $nonce_action ) ) {
				if ( EPME_Authorization::is_authorization() ) {
					return array();
				} else {
					$answer = epme_error( __( 'Not authorization', 'easyping.me' ) );
				}
			} else {
				$answer = epme_error( __( 'Verification problem. Refresh the page', 'easyping.me' ) );
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		return $answer;
	}

	/**
	 * First connect with Sign in
	 */
	public static function sign_in_ajax() {
		check_ajax_referer( 'easyping.me', 'nonce_code' );

		self::if_authorization_then_platform();

		$sign_in_answer = EPME_Connects::sign_in();
		if ( $sign_in_answer['type'] == 'ok' ) {
			$answer = array(
				'answer' => $sign_in_answer['respond'],
			);
			$answer += epme_ok();
		} else {
			$answer = epme_error( $sign_in_answer['cause'] );
		}
		echo json_encode( $answer );
		die;
	}

	/**
	 * Classic Sign in with login and password
	 */
	public static function classic_sign_in() {
		check_ajax_referer( 'easyping.me', 'nonce_code' );

		self::if_authorization_then_platform();
		$login = $_POST['login'];
		$password = $_POST['password'];

		$sign_in_answer = EPME_Connects::classic_sign_in( $login, $password );
		if ( $sign_in_answer['type'] == 'ok' ) {
			$answer = EPME_Authorization::sign_in( $sign_in_answer['respond'] );
			self::if_authorization_then_platform();
		} else {
			$answer = epme_error( $sign_in_answer['cause'] );
		}
		echo json_encode( $answer );
		die;
	}

	/**
	 * Get Platform authorization part.
	 */
	public static function if_authorization_then_platform() {
		if ( EPME_Authorization::is_authorization() ) {
			$answer = array(
				'html' => EPME_Admin_Platform::output( true, false ),
			);
			$answer += epme_ok();
			echo json_encode( $answer );
			die;
		}
	}

	/**
	 * Widget List (input checkbox)
	 */
	public static function widgets_list() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				$server_answer = EPME_Connects::channel_read_list();
				if ( $server_answer['type'] == 'ok' ) {
					EPME_Channels::get_channels_data( $server_answer );
					$answer = array(
						'html' => EPME_Admin_Widgets::channels_list_template(),
					);
					$answer += epme_ok();
				} else {
					$answer = epme_error( $server_answer['cause'] );
				}
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Widget List (input checkbox)
	 */
	public static function widgets_template() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				$server_answer = EPME_Connects::channel_read_list();
				if ( $server_answer['type'] == 'ok' ) {
					EPME_Channels::get_channels_data( $server_answer );
					$answer = array(
						'html' => EPME_Admin_Widgets::widgets_template(),
					);
					$answer += epme_ok();
				} else {
					$answer = epme_error( $server_answer['cause'] );
				}
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Create widget.
     *
	 * @param  array $respond_param
	 */
	public static function create_widget( $respond_param = array() ) {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
                $respond_POST = epme_fix_bool_in_arr( $_POST['respond'] );
                if ( !empty( $respond_POST ) ) {
	                $respond_POST['token'] = EPME_Authorization::get_token();
	                $respond_param = $respond_POST;
                }

				$server_answer = EPME_Connects::create_widget( $respond_param );
				if ( $server_answer['type'] == 'ok' ) {
					if ( isset( $server_answer['respond']->id ) AND $server_answer['respond']->id ) {
						$answer = array(
							'answer' => $server_answer,
							'message' => __( 'Widget created', 'easyping.me' ),
						);
						$answer += epme_ok();

						$param['id'] = $server_answer['respond']->id;
						$param['token'] = EPME_Authorization::get_token();
						$param['active'] = false;
						$param['buttonColor'] = '#000000';
						$param['textColor'] = '#eb7a1e';
						$param['withEmail'] = true;
						EPME_Connects::update_widget( $param );
					} else {
						// if free connect
						if ( ( float )$server_answer['respond']->changeBalance->amount == 0 ) {
                            if ( empty( $respond_param ) ) {
	                            $respond          = epme_fix_bool_in_arr( $server_answer['respond'] );
	                            $respond['token'] = EPME_Authorization::get_token();

	                            self::create_widget( $respond );
                            } else {
	                            $answer = epme_error( 'Infinite recursion', $server_answer['respond'] );
                            }
						} else {
							if ( isset( $server_answer['respond']->token ) ) {
								unset( $server_answer['respond']->token );
							}
							$answer = array(
								'answer' => $server_answer,
//								'message' => __( 'Widget created', 'easyping.me' ),
							);
							$answer += epme_ok();
						}
					}
				} else {
					$answer = epme_error( $server_answer['cause'] );
				}
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Update widget.
     *
	 * @param  array $respond_param
	 */
	public static function update_widget( $respond_param = array() ) {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				$respond_POST = epme_fix_bool_in_arr( $_POST['respond'] );

				// if not Withdrawals form (if raw data)
				if ( !isset( $respond_POST['progressive'] ) OR !$respond_POST['progressive'] ) {
					$params = $respond_POST['widget'];
					$params['id'] = ( isset( $respond_POST['id'] ) ) ? $respond_POST['id'] : $params['id'];
					$params['withEmail'] = ( isset( $respond_POST['withEmail'] ) ) ? $respond_POST['withEmail'] : $params['withEmail'];
					$params['name'] = ( isset( $respond_POST['name'] ) ) ? $respond_POST['name'] : $params['name'];
					$params['token'] = EPME_Authorization::get_token();
					$params['channels'] = array();

					foreach ( $respond_POST['init_channels'] as $channel ) {
						if ( $channel !== 'mail' )
							$params['channels'][] = $channel;
					}

					if ( !empty( $respond_param ) ) {
						$params = array_merge( $respond_param, $params );
					}
                } else {
					$params = $respond_POST;
					$params['token'] = EPME_Authorization::get_token();
                }

				$server_answer = EPME_Connects::update_widget( $params );
				if ( $server_answer['type'] == 'ok' ) {
					if ( isset( $server_answer['respond']->id ) AND $server_answer['respond']->id ) {

					    // if changeBalance exist
                        if ( isset( $server_answer['respond']->changeBalance ) AND isset( $server_answer['respond']->changeBalance->amount ) ) {

	                        // if free connect
	                        if ( ( float )$server_answer['respond']->changeBalance->amount == 0 ) {
		                        if ( empty( $respond_param ) ) {
			                        $respond          = epme_fix_bool_in_arr( $server_answer['respond'] );
			                        $respond['token'] = EPME_Authorization::get_token();

			                        self::update_widget( $respond );
		                        } else {
			                        $answer = epme_error( 'Infinite recursion', $server_answer['respond'] );
		                        }
	                        } else {
		                        if ( isset( $server_answer['respond']->token ) ) {
			                        unset( $server_answer['respond']->token );
		                        }
		                        $answer = array(
			                        'answer' => $server_answer,
		                        );
		                        $answer += epme_ok();
	                        }
                        } else {
	                        if ( isset( $respond_POST['progressive'] ) AND $respond_POST['progressive'] ) {
		                        $m = __( 'Widget updated but deactivated', 'easyping.me' );
	                        } else {
		                        $m = __( 'Widget updated', 'easyping.me' );
                            }

                            $answer = array(
                                'answer' => $server_answer,
                                'message' => $m,
                            );
                            $answer += epme_ok();
                        }
					}
				} else {
					$answer = epme_error( $server_answer['cause'] );
				}
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Delete widget.
	 */
	public static function delete_widget() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
                $id = intval( $_POST['id'] );

                if ( $id ) {
	                $server_answer = EPME_Connects::delete_widget( $id );
	                if ( $server_answer['type'] == 'ok' ) {
		                if ( isset( $server_answer['respond']->success ) AND $server_answer['respond']->success ) {
			                $answer = array(
				                'answer' => $server_answer,
				                'message' => $server_answer['respond']->success,
			                );
			                $answer += epme_ok();
		                }
	                } else {
		                $answer = epme_error( $server_answer['cause'] );
	                }
                } else {
	                $answer = epme_error( __( 'Empty id', 'easyping.me' ) );
                }
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Deactivate/Activate widget.
	 */
	public static function active_widget() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
			    $respond = epme_fix_bool_in_arr( $_POST['respond'] );
			    $respond['token'] = EPME_Authorization::get_token();

                if ( isset( $_POST['respond']['id'] ) AND $_POST['respond']['id'] ) {
	                $server_answer = EPME_Connects::active_widget( $respond );
	                if ( $server_answer['type'] == 'ok' ) {
		                if ( isset( $server_answer['respond']->id ) AND $server_answer['respond']->id ) {

			                // if changeBalance exist
			                if ( isset( $server_answer['respond']->changeBalance ) AND isset( $server_answer['respond']->changeBalance->amount ) ) {
			                    if ( isset( $server_answer['respond']->changeBalance->complete ) AND $server_answer['respond']->changeBalance->complete ) {
				                    $message = __( 'Success', 'easyping.me' );
			                    } else {
				                    $message = '';
                                }
			                } else {
				                $message = __( 'Success', 'easyping.me' );
                            }
			                $answer = array(
				                'answer' => $server_answer,
				                'message' => $message,
			                );
			                $answer += epme_ok();
		                }
	                } else {
		                $answer = epme_error( $server_answer['cause'] );
	                }
                } else {
	                $answer = epme_error( __( 'Empty id', 'easyping.me' ) );
                }
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Change Country by ajax
	 */
	public static function change_country() {
		$country = addslashes( $_POST['country'] );
		$server_answer = self::check_to_error( 'epme_change_country', 'epme_change_country_field', $country, 'change_country' );

		$country_short = strtolower( $server_answer['respond']->country );
		EPME_Authorization::set_country( $country_short );
		$answer = array(
			'type' => 'change_country',
			'val' => $country_short,
			'country' => EPME_Admin_Data::get_country_name_by_slug( $country_short ),
		);
		$answer += epme_ok();

		echo json_encode( $answer );
		die;
	}

	/**
	 * Change Project name by ajax
	 */
	public static function change_name() {
		$project = $_POST['project'];
		$server_answer = self::check_to_error( 'epme_change_name', 'epme_change_name_field', $project, 'change_name' );

		EPME_Authorization::set_project_name( addslashes( $server_answer['respond']->project ) );
		$answer = array(
			'type' => 'change_name',
			'new_name' => $server_answer['respond'],
		);
		$answer += epme_ok();

		echo json_encode( $answer );
		die;
	}

	/**
	 * Add redeem code
	 */
	public static function add_redeem() {
		$redeem = $_POST['redeem'];
		$server_answer = self::check_to_error( 'epme_add_redeem', 'epme_add_redeem_field', $redeem, 'add_redeem' );

		$answer = array(
			'message' => __( 'Success', 'easyping.me' ),
		);
		$answer += epme_ok();

		echo json_encode( $answer );
		die;
	}

	/**
	 * Sign out by ajax.
	 */
	public static function sign_out_ajax() {
		check_ajax_referer( 'easyping.me', 'nonce_code' );

		EPME_Authorization::sign_out();

		$answer = array(
			'status' => array(
				'type' => 'ok',
				'cause' => '',
			),
			'html' => EPME_Admin_Platform::output( true, false )
		);
		echo json_encode( $answer );
		die;
	}

	/**
	 * Add funds via ajax.
	 */
	public static function add_funds_ajax() {
		$funds = (float)addslashes( $_POST['funds'] );
		$funds = ( $funds ) ? $funds : '10.00';
		$server_answer = self::check_to_error( 'easyping.me', 'nonce_code', $funds, 'add_funds' );

		ob_start();
		?>
		<form action="<?php echo $server_answer['respond']->action; ?>" method="<?php echo $server_answer['respond']->method; ?>" class="epme-paylane-form">
			<?php
			$miss_fields = array( 'id', 'paySystem', 'method', 'action', 'status' );
			foreach ( $server_answer['respond'] as $key => $val ) {
				if ( ( is_string( $val ) OR is_float( $val ) OR is_int( $val ) ) AND !in_array( $val, $miss_fields ) ) {
					echo "<input type=\"hidden\" name=\"{$key}\" value=\"{$val}\">";
				}
			}
			?>
		</form>
		<?php
		$html = ob_get_clean();

		$answer = array(
//			'answer' => $server_answer,
			'html' => $html,
		);
		$answer += epme_ok();

		echo json_encode( $answer );
		die;
	}

	/**
	 * Save channel via ajax (first stage).
	 */
	public static function save_channel() {
		try {
            $channel = addslashes( $_POST['channel'] );
            $name = addslashes( $_POST['name'] );
            $token = addslashes( $_POST['token'] );
            $id = addslashes( $_POST['id'] );
            $active = ( $_POST['active'] == 'on' ) ? true : false;

			$error_core = self::check_to_error_core( 'epme_channel', 'epme_channel_field' );
			if ( empty( $error_core ) ) {
			    if ( !$id OR $id == 'empty' ) {
				    $server_answer = EPME_Connects::create_channel( $channel, $token, $name );
                } else {
				    $server_answer = EPME_Connects::edit_channel( $channel, $token, $name, $id, $active );
                }
                if ( $server_answer['type'] == 'ok' ) {
                    if ( isset( $server_answer['respond']->id ) AND $server_answer['respond']->id ) {
	                    $answer = array(
		                    'answer' => $server_answer,
		                    'message' => __( 'Channel edited successfully', 'easyping.me' ),
	                    );
	                    $answer += epme_ok();
                    } else {
	                    // if free connect
	                    if ( ( float )$server_answer['respond']->changeBalance->amount == 0 ) {
		                    self::save_channel_with_agree( $server_answer['respond'] );
	                    } else {
		                    if ( isset( $server_answer['respond']->token ) ) {
			                    unset( $server_answer['respond']->token );
		                    }
		                    $answer = array(
			                    'answer' => $server_answer,
			                    'message' => __( 'Channel created successfully', 'easyping.me' ),
		                    );
		                    $answer += epme_ok();
	                    }
                    }
                } else {
                    $answer = epme_error( $server_answer['cause'] );
                }
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Save channel with authorize via ajax (first stage Twitter, FB, VK).
	 */
	public static function channel_with_authorize() {
		try {
			$prefix = addslashes( $_POST['prefix'] );

			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				$server_answer = EPME_Connects::network_oauth_url( $prefix );

				if ( $server_answer['type'] == 'ok' ) {
					$answer = array(
						'answer' => $server_answer,
					);
					$answer += epme_ok();
				} else {
					$answer = epme_error( $server_answer['cause'] );
				}
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Save channel with processed (authorize) via ajax (second stage FB, VK).
	 */
	public static function save_channel_processed() {
		try {
            $channel = addslashes( $_POST['channel'] );
            $group_select = unserialize( base64_decode( $_POST['epme-group-select'] ) );
			$changeBalance = $_POST['change_balance'];
			if ( !is_array( $changeBalance ) OR empty( $changeBalance ) ) {
				$changeBalance = array();
            }

			$error_core = self::check_to_error_core( 'epme_channel', 'epme_channel_field' );
			if ( empty( $error_core ) ) {
			    if ( $group_select['id'] AND $group_select['url'] ) { //VK
				    wp_redirect( $group_select['url'] );
                } else {
				    if ( $group_select['id'] AND $group_select['token'] ) { //FB
					    $server_answer = EPME_Connects::create_channel_processed( $channel, (string)$group_select['id'], $group_select['token'], $changeBalance );
					    if ( $server_answer['type'] == 'ok' ) {
						    if ( isset( $server_answer['respond']->active ) ) {
							    delete_option( 'EASYPING_OAUTH' );
							    $answer = array(
								    'answer'  => $server_answer,
								    'message' => __( 'Channel created successfully', 'easyping.me' ),
							    );
							    $answer += epme_ok();
						    } else {
							    // if free connect
							    if ( isset( $server_answer['respond']->changeBalance->amount ) AND ( float ) $server_answer['respond']->changeBalance->amount == 0 ) {
								    $respond          = epme_fix_bool_in_arr( $server_answer['respond'] );
								    $respond['token'] = EPME_Authorization::get_token();
								    if ( isset( $respond['id'] ) ) {
									    $respond['id'] = ( string ) $respond['id'];
								    }

								    $server_answer = EPME_Connects::create_channel_processed( '', '', '', $respond );

								    if ( $server_answer['type'] == 'ok' ) {
									    delete_option( 'EASYPING_OAUTH' );
									    $answer = array(
										    'answer'  => $server_answer,
										    'message' => __( 'Channel created successfully.', 'easyping.me' ),
									    );
									    $answer += epme_ok();
								    } else {
									    $answer = epme_error( $server_answer['cause'] );
								    }
							    } else {
								    if ( isset( $server_answer['respond']->token ) ) {
									    unset( $server_answer['respond']->token );
								    }
								    $answer = array(
									    'answer' => $server_answer,
								    );
								    $answer += epme_ok();
							    }
						    }
					    } else {
						    $answer = epme_error( $server_answer['cause'] );
					    }
				    } else {
					    $answer = epme_error( 'Empty value' );
				    }
			    }
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Save channel via ajax (second stage with AGREE).
     *
     * @param  array $respond
	 */
	public static function save_channel_with_agree( $respond = array() ) {
		try {
		    if ( empty( $respond ) ) {
			    $respond = $_POST['respond'];
			    $respond = epme_fix_bool_in_arr( $respond );
			    $respond['token'] = EPME_Authorization::get_token();
			    $error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
		    }

			if ( empty( $error_core ) ) {
				$server_answer = EPME_Connects::create_channel_with_agree( $respond );
                if ( $server_answer['type'] == 'ok' ) {
                    if ( isset( $server_answer['respond']->token ) ) {
	                    unset( $server_answer['respond']->token );
                    }

                    if ( $server_answer['respond']->active === true AND $server_answer['respond']->network AND $server_answer['respond']->networkToken ) {
	                    $answer = array(
		                    'answer' => $server_answer,
		                    'message' => __( 'The channel has been connected.', 'easyping.me' ),
	                    );
	                    $answer += epme_ok();
                    } else {
	                    $answer = epme_error( __( 'Incomplete data from server.', 'easyping.me' ) );
                    }
                } else {
                    $answer = epme_error( $server_answer['cause'] );
                }
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Sign out by ajax
	 */
	public static function refresh_channels() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				$answer = array(
					'status' => array(
						'type' => 'ok',
						'cause' => '',
					),
					'html' => EPME_Admin_Channels::output( true, false )
				);
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Save channel via ajax (first stage).
	 */
	public static function delete_oauth_progress() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				delete_option( 'EASYPING_OAUTH' );
				$answer = epme_ok();
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Create Campaign.
	 */
	public static function create_campaign() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				if ( isset( $_POST['data'] ) AND isset( $_POST['data']['name'] ) AND $_POST['data']['name'] ) {
                    $note = ( $_POST['data']['note'] ) ? addslashes( $_POST['data']['note'] ) : '';
					$id = ( $_POST['data']['id'] ) ? intval( $_POST['data']['id'] ) : '';

                    if ( !$id ) {
	                    $server_answer = EPME_Connects::create_campaign( $_POST['data']['name'], $note );
	                    $message = __( 'Campaign has been created', 'easyping.me' );
                    } else {
                        $masterMessageBlock = $_POST['data']['masterMessageBlock'];
                        $messageBlocks = $_POST['data']['messageBlocks'];
                        $messageBlocks_result = array();
                        $date = $_POST['data']['date'];
                        $time = $_POST['data']['time'];
                        $time_zone = ( $_POST['data']['time_zone'] >= 0 ) ? "+{$_POST['data']['time_zone']}" : $_POST['data']['time_zone'];
                        $date_result = date( 'Y-m-d\TH:i:s', strtotime( "$date $time $time_zone" ) );

                        // Convert to API format
	                    foreach ( $messageBlocks as $message_block ) {
                            if ( !is_array( $messageBlocks_result[$message_block['socialNetworkType']] ) )
	                            $messageBlocks_result[$message_block['socialNetworkType']] = [];
		                    $messageBlocks_result[$message_block['socialNetworkType']][] = $message_block;
                        }

                        $server_answer = EPME_Connects::update_campaign( $id, $_POST['data']['name'], $note, $date_result );
	                    $message = __( 'Campaign has been updated', 'easyping.me' );
                    }
					if ( $server_answer['type'] == 'ok' ) {
						$server_answer2 = EPME_Connects::message_block_create( $id, $masterMessageBlock, $messageBlocks_result );
						$message = __( 'Campaign and Message blocks are updated', 'easyping.me' );

                        $answer = array(
                            'answer' => $server_answer,
                            'answer2' => $server_answer2,
                            'message' => $message,
                        );
                        $answer += epme_ok();
					} else {
						$answer = epme_error( $server_answer['cause'] );
					}
				} else {
					$answer = epme_error( __( 'Empty Name', 'easyping.me' ) );
				}
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Delete Campaign.
	 */
	public static function delete_campaign() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				if ( isset( $_POST['id'] ) AND $_POST['id'] ) {
                    $server_answer = EPME_Connects::delete_campaign( ( int )$_POST['id'] );
					if ( $server_answer['type'] == 'ok' ) {
                        $answer = array(
                            'answer' => $server_answer,
                            'message' => __( 'Campaign has been deleted', 'easyping.me' ),
                        );
                        $answer += epme_ok();
					} else {
						$answer = epme_error( $server_answer['cause'] );
					}
				} else {
					$answer = epme_error( __( 'Empty ID', 'easyping.me' ) );
				}
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Reload Campaigns.
	 */
	public static function reload_campaigns() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				$answer = array(
					'html' => EPME_Admin_Campaigns::campaigns_table_template(),
				);
				$answer += epme_ok();
			} else {
				$answer = $error_core;
			}
		} catch ( Exception $e ) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Reload Campaigns.
	 */
	public static function reload_campaign_footer() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
                $blocks = new EPME_Blocks();
                $type = ( isset( $_POST['type'] ) ) ? addslashes( $_POST['type'] ) : 'master';
				$answer = array(
					'html' => $blocks->campaign_footer_template( $type ),
				);
				$answer += epme_ok();
			} else {
				$answer = $error_core;
			}
		} catch ( Exception $e ) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Create subscriber's list.
	 */
	public static function create_subscriber_list() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				if ( isset( $_POST['data'] ) AND isset( $_POST['data']['name'] ) AND $_POST['data']['name'] ) {
					$note = ( $_POST['data']['note'] ) ? addslashes( $_POST['data']['note'] ) : '';
					$filters = ( $_POST['data']['filters'] ) ? addslashes( $_POST['data']['filters'] ) : '';
					$id = ( $_POST['data']['id'] ) ? intval( $_POST['data']['id'] ) : '';

					if ( !$id ) {
						$server_answer = EPME_Connects::create_subscriber_list( $_POST['data']['name'], $filters, $note );
						$message = __( 'Subscribers list template has been created', 'easyping.me' );
					} else {
						$server_answer = EPME_Connects::update_subscriber_list( $id, $_POST['data']['name'], $filters, $note );
						$message = __( 'Subscribers list template has been updated', 'easyping.me' );
					}
					if ( $server_answer['type'] == 'ok' ) {
						$answer = array(
							'answer' => $server_answer,
							'message' => $message,
						);
						$answer += epme_ok();
					} else {
						$answer = epme_error( $server_answer['cause'] );
					}
				} else {
					$answer = epme_error( __( 'Empty Name', 'easyping.me' ) );
				}
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Create subscriber's list.
	 */
	public static function filters_done() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				if ( isset( $_POST['campaign_id'] ) AND $_POST['campaign_id'] ) {
					$filters = ( !empty( $_POST['filters'] ) ) ? $_POST['filters'] : array();
					$campaign_id = intval( $_POST['campaign_id'] );
					$data = array(
						'token' => EPME_Authorization::get_token(),
						'campaignId' => $campaign_id,
                    );
					$data = $data + $filters;

                    $server_answer = EPME_Connects::filters_done( $data );
					if ( $server_answer['type'] == 'ok' ) {
						$answer = array(
							'answer' => $server_answer,
							'data' => $data,
						);
						$answer += epme_ok();
					} else {
						$answer = epme_error( $server_answer['cause'] );
					}
				} else {
					$answer = epme_error( __( 'Empty Campaign ID', 'easyping.me' ) );
				}
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Delete Tester.
	 */
	public static function delete_tester() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				if ( isset( $_POST['id'] ) AND $_POST['id'] ) {
					$server_answer = EPME_Connects::delete_tester( ( int )$_POST['id'] );
					if ( $server_answer['type'] == 'ok' ) {
						$answer = array(
							'answer' => $server_answer,
							'message' => __( 'Tester has been deleted', 'easyping.me' ),
						);
						$answer += epme_ok();
					} else {
						$answer = epme_error( $server_answer['cause'] );
					}
				} else {
					$answer = epme_error( __( 'Empty ID', 'easyping.me' ) );
				}
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Send to preview.
	 */
	public static function send_to_preview() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				if ( !empty( $_POST['data'] ) ) {
					$content_type = ( $_POST['content_type'] ) ? addslashes( $_POST['content_type'] ) : '';
					$data = $_POST['data'];

					if ( $content_type == 'master' OR !$content_type )
						$content_type = 'all';

					$server_answer = EPME_Connects::send_to_preview( $content_type, $data );
					if ( $server_answer['type'] == 'ok' ) {
						$answer = array(
							'answer' => $server_answer,
							'message' => __( 'The message has been send successfully. Check your channels', 'easyping.me' ),
						);
						$answer += epme_ok();
					} else {
						$answer = epme_error( $server_answer['cause'] );
					}
				} else {
					$answer = epme_error( __( 'Data is empty', 'easyping.me' ) );
				}
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Sign out by ajax.
	 */
	public static function refresh_testers() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				$answer = array(
					'status' => array(
						'type' => 'ok',
						'cause' => '',
					),
					'html' => EPME_Admin_Subscribers::testers()
				);
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}

	/**
	 * Welcome form complete.
	 */
	public static function welcome_form_complete() {
		try {
			$error_core = self::check_to_error_core( 'easyping.me', 'nonce_code' );
			if ( empty( $error_core ) ) {
				update_option( 'epme-welcome-form--' . EPME_Authorization::get_email(), 1 );
				$answer = array(
					'status' => array(
						'type' => 'ok',
						'cause' => '',
					),
                    'message' => __( "Thank you!", 'easyping.me' ),
				);
			} else {
				$answer = $error_core;
			}
		} catch (Exception $e) {
			$answer = epme_error( __( "Exception ", 'easyping.me' ) . $e->getMessage() );
		}

		echo json_encode( $answer );
		die;
	}
}
