<?php
/**
 * @version 5.2.0
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$settings = bp_email_get_appearance_settings();

?><!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
	<meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
	<title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

	<!-- CSS Reset -->
	<style type="text/css">
		/* What it does: Remove spaces around the email design added by some email clients. */
		/* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
		html,
		body {
			Margin: 0 !important;
			padding: 0 !important;
			height: 100% !important;
			width: 100% !important;
		}

		pre {
			background: #F5F6F7;
			border: 1px solid rgba(0, 0, 0, 0.03);
			margin: 0 auto;
			overflow: auto;
			padding: 10px;
			white-space: pre-wrap;
			font-size: 14px !important;
			border-radius: 4px;
		}

		blockquote {
			background: #e3e6ea;
			border-radius: 4px;
			padding: 12px;
			font-size: 20px;
			font-style: italic;
			font-weight: normal;
			letter-spacing: -0.24px;
			line-height: 30px;
			position: relative;
			margin: 0 0 15px 0;
		}

		blockquote p {
			margin: 0;
		}

		/* What it does: Stops email clients resizing small text. */
		* {
			-ms-text-size-adjust: 100%;
			-webkit-text-size-adjust: 100%;
		}

		/* What is does: Centers email on Android 4.4 */
		div[style*="margin: 16px 0"] {
			margin: 0 !important;
		}

		/* What it does: Stops Outlook from adding extra spacing to tables. */
		table,
		td {
			mso-table-lspace: 0pt !important;
			mso-table-rspace: 0pt !important;
		}

		/* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
		table {
			border-spacing: 0 !important;
			border-collapse: collapse !important;
			table-layout: fixed !important;
			margin: 0 auto !important;
		}

		table table table {
			table-layout: auto;
		}

		/* What it does: Uses a better rendering method when resizing images in IE. */
		/* & manages img max widths to ensure content body images don't exceed template width. */
		img {
			-ms-interpolation-mode:bicubic;
			height: auto;
			max-width: 100%;
		}

		/* What it does: A work-around for email clients meddling in triggered links. */
		*[x-apple-data-detectors],  /* iOS */
		.x-gmail-data-detectors,    /* Gmail */
		.x-gmail-data-detectors *,
		.aBn {
			border-bottom: 0 !important;
			cursor: default !important;
			color: inherit !important;
			text-decoration: none !important;
			font-size: inherit !important;
			font-family: inherit !important;
			font-weight: inherit !important;
			line-height: inherit !important;
		}

		/* What it does: Prevents Gmail from displaying an download button on large, non-linked images. */
		.a6S {
			display: none !important;
			opacity: 0.01 !important;
		}

		/* If the above doesn't work, add a .g-img class to any image in question. */
			img.g-img + div {
			display: none !important;
		}

		/* What it does: Prevents underlining the button text in Windows 10 */
		.button-link {
			text-decoration: none !important;
		}

		/* Remove links underline */
		a:not(.ab-item), .ii a[href] {
			color: <?php echo esc_attr( $settings['highlight_color'] ); ?> !important;
			text-decoration: none !important;
		}

		/* What it does: Forces Outlook.com to display emails full width. */
		.ExternalClass {
			width: 100%;
		}

		.recipient_text_color table {
			display: inline-table;
		}

		/* MOBILE STYLES */
		@media screen and (max-width: 525px) {
			/* ALLOWS FOR FLUID TABLES */
			.wrapper {
				width: 100% !important;
				max-width: 100% !important;
			}

			/* ADJUSTS LAYOUT OF LOGO IMAGE */
			.logo img {
				margin: 0 auto !important;
			}

			/* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
			.mobile-hide {
				display: none !important;
			}

			.img-max {
				max-width: 100% !important;
				width: 100% !important;
				height: auto !important;
			}

			/* FULL-WIDTH TABLES */
			.responsive-table {
				width: 100% !important;
			}

			.mobile-text-center {
				text-align: center !important;
			}

			.mobile-text-left {
				text-align: left !important;
			}

			.repsonsive-padding {
				padding: 0 20px !important;
			}

			.responsive-set-height {
				font-size: 0 !important;
				line-height: 0 !important;
				height: 0 !important;
			}

			.mobile-block-full {
				display: block !important;
				width: 100% !important;
			}

			.mobile-block-padding-full {
				display: block !important;
				padding: 0 20px !important;
				width: 100% !important;
				box-sizing: border-box;
			}

			.avatar-wrap.mobile-center {
				margin: 20px auto 10px !important;
			}

			.group-avatar-wrap.mobile-center {
				margin: 10px auto 20px !important;
			}

			.mobile-padding-bottom {
				padding-bottom: 10px !important;
			}

			.mobile-button-center {
				margin: 5px auto 0 !important;
				width: 160px !important;
			}
		}
	</style>
</head>

<body class="email_bg" width="100%" bgcolor="<?php echo esc_attr( $settings['email_bg'] ); ?>" style="margin: 0; mso-line-height-rule: exactly;">
	<table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="<?php echo esc_attr( $settings['email_bg'] ); ?>" style="border-collapse:collapse;" class="email_bg"><tr><td valign="top">
		<center style="width: 100%; text-align: <?php echo esc_attr( $settings['direction'] ); ?>;">

		<!-- Visually Hidden Preheader Text : BEGIN -->
		<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
			{{email.preheader}}
		</div>
		<!-- Visually Hidden Preheader Text : END -->

		<div style="max-width: 600px; margin: auto; padding: 10px;" class="email-container">
			<!--[if mso]>
			<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
			<tr>
			<td>
			<![endif]-->

			<!-- Email Header : BEGIN -->
			<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
				<tr>
					<td style="text-align: left; padding: 50px 0 30px 0; font-family: sans-serif; mso-height-rule: exactly; font-weight: bold; color: <?php echo esc_attr( $settings['site_title_text_color'] ); ?>; font-size: <?php echo esc_attr( $settings['site_title_text_size'] . 'px' ); ?>" class="site_title_text_color site_title_text_size">
						<?php
						/**
						 * Fires before the display of the email template header.
						 *
						 * @since BuddyPress 2.5.0
						 */
						do_action( 'bp_before_email_header' );

						$blogname = bp_get_option( 'blogname' );
						$attachment_id = isset( $settings[ 'logo' ] ) ? $settings[ 'logo' ] : '';

						if ( !empty( $attachment_id ) ) {
							$image_src = wp_get_attachment_image_src( $attachment_id, array( 180, 45 ) );
							if ( !empty( $image_src ) ) { ?>
								<img src="<?php echo esc_attr( $image_src[ 0 ] ); ?>" alt="<?php echo esc_attr( $blogname ); ?>" style="margin:0; padding:0; border:none; display:block; max-height:auto; height:auto; width:<?php echo esc_attr( $settings['site_title_logo_size'] ); ?>px;" border="0" /><?php
							} else {
								echo $blogname;
							}
						} else {
							echo $blogname;
						}

						/**
						 * Fires after the display of the email template header.
						 *
						 * @since BuddyPress 2.5.0
						 */
						do_action( 'bp_after_email_header' );
						?>
					</td>
					<td style="text-align: right; padding: 50px 0 30px 0; font-family: sans-serif; mso-height-rule: exactly; font-weight: normal; color: <?php echo esc_attr( $settings['recipient_text_color'] ); ?>; font-size: <?php echo esc_attr( $settings['recipient_text_size'] . 'px' ); ?>" class="recipient_text_color recipient_text_size">
						<?php
						/**
						 * Fires before the display of the email recipient.
						 *
						 * @since BuddyBoss 1.0.0
						 */
						do_action( 'bp_before_email_recipient' );

						/**
						 * Fires after the display of the email recipient.
						 *
						 * @since BuddyBoss 1.0.0
						 */
						do_action( 'bp_after_email_recipient' );
						?>
					</td>
				</tr>
			</table>
			<!-- Email Header : END -->

			<!-- Email Body : BEGIN -->

				<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="<?php echo esc_attr( $settings['body_bg'] ); ?>" width="100%" style="border-collapse: separate !important; max-width: 600px; border-radius: 5px; border: 1px solid <?php echo esc_attr( $settings['body_border_color'] ); ?>" class="body_bg body_border_color">

					<!-- 1 Column Text : BEGIN -->
					<tr>
						<td>
							<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
								<tr>
									<td style="padding: 20px 40px; font-family: sans-serif; mso-height-rule: exactly; line-height: <?php echo esc_attr( floor( $settings['body_text_size'] * 1.618 ) . 'px' ); ?>; color: <?php echo esc_attr( $settings['body_text_color'] ); ?>; font-size: <?php echo esc_attr( $settings['body_text_size'] . 'px' ); ?>" class="body_text_color body_text_size repsonsive-padding">
									