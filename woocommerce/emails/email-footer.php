<?php
/**
 * @version 5.2.0
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$settings = bp_email_get_appearance_settings();

?>
								</tr>
							</table>
						</td>
					</tr>
					<!-- 1 Column Text : BEGIN -->

				</table>
			<!-- Email Body : END -->

			<!-- Email Footer : BEGIN -->
			<br>
			<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="<?php echo esc_attr( $settings['direction'] ); ?>" width="100%" style="max-width: 600px; border-radius: 5px;">
				<tr>
					<td style="padding: 20px 40px; width: 100%; font-size: <?php echo esc_attr( $settings['footer_text_size'] . 'px' ); ?>; font-family: sans-serif; mso-height-rule: exactly; line-height: <?php echo esc_attr( floor( $settings['footer_text_size'] * 1.618 ) . 'px' ); ?>; text-align: center; color: <?php echo esc_attr( $settings['footer_text_color'] ); ?>;" class="footer_text_color footer_text_size repsonsive-padding">
						<?php
						/**
						 * Fires before the display of the email template footer.
						 *
						 * @since BuddyPress 2.5.0
						 */
						do_action( 'bp_before_email_footer' );
						?>

						<span class="footer_text"><?php echo nl2br( stripslashes( $settings['footer_text'] ) ); ?></span>
						<p style="margin: 5px 0;"><?php _e( "If you don't want to receive these emails in the future, please ", 'buddyboss' ); ?><a href="{{{unsubscribe}}}" style="text-decoration: none;"><?php esc_html_e( 'unsubscribe', 'buddyboss' ); ?></a>.</p>

						<?php
						/**
						 * Fires after the display of the email template footer.
						 *
						 * @since BuddyPress 2.5.0
						 */
						do_action( 'bp_after_email_footer' );
						?>
					</td>
				</tr>
				<tr>
					<td height="45px" style="font-size: 45px; line-height: 45px;">&nbsp;</td>
				</tr>
			</table>
			<!-- Email Footer : END -->

			<!--[if mso]>
			</td>
			</tr>
			</table>
			<![endif]-->
		</div>
	</center>
</td></tr>
</table>
<?php
if ( function_exists( 'is_customize_preview' ) && is_customize_preview() ) {
	wp_footer();
}
?>
</body>
</html>
