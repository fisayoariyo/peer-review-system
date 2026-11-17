<?php

/**
 * Email Settings
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/admin/Plugin_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */

/* email general setting tab */
Redux::set_section($opt_name, array(
	'title'       => esc_html__('Email settings', 'peer-review-system'),
	'id'          => 'email_settings',
	'desc'        => '',
	'icon'        => 'el el-inbox',
	'subsection'  => false,
	'fields'      => array(
		array(
			'id'      => 'divider_1',
			'type'    => 'info',
			'title'   => esc_html__('Email General Settings', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'email_logo',
			'type'    => 'media',
			'compiler' => 'true',
			'url'     => true,
			'title'   => esc_html__('Email Logo', 'peer-review-system'),
			'desc'    => esc_html__('Upload your email logo here.', 'peer-review-system'),
		),
		array(
			'id'      => 'email_sender_name',
			'type'    => 'text',
			'title'   => esc_html__('Email sender name', 'peer-review-system'),
			'desc'    => esc_html__('Add email sender name here like: Shawn Biyeam. Default your site name will be used.', 'peer-review-system'),
			'default' => esc_html__('Amentotech.Pvt.Ltd', 'peer-review-system'),
		),
		array(
			'id'      => 'email_sender_email',
			'type'    => 'text',
			'title'   => esc_html__('Email sender email', 'peer-review-system'),
			'desc'    => esc_html__('Add email sender email here like: johndoe@example.com. Default your site email will be used.', 'peer-review-system'),
			'default' => esc_html__('johndoe@example.com', 'peer-review-system'),
		),
		array(
			'id'      => 'email_copyrights',
			'type'    => 'textarea',
			'title'   => esc_html__('Footer copyright text', 'peer-review-system'),
			'desc'    => esc_html__('Add copyright text for the emails in footer', 'peer-review-system'),
		),
		array(
			'id'      => 'email_signature',
			'type'    => 'textarea',
			'title'   => esc_html__('Email Sender Signature ', 'peer-review-system'),
			'desc'    => esc_html__('Add email sender signature here', 'peer-review-system'),
			'default' => esc_html__('Regards,', 'peer-review-system'),
		),
	)
));

/* email administrator setting tab */
Redux::set_section($opt_name, array(
	'title'			=> esc_html__('Administrator', 'peer-review-system'),
	'id'			=> 'administrator_email_templates',
	'desc'			=> 'Administrator email templates',
	'icon'			=> '',
	'subsection'	=> true,
	'fields'		=> array(
		/* Admin Email to verify new user */
		array(
			'id'      => 'divider_verify_user_admin_registration_templates',
			'type'    => 'info',
			'title'   => esc_html__('Admin email verify user', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'admin_verify_register_user_subject',
			'type'    => 'text',
			'title'   => esc_html__('Subject', 'peer-review-system'),
			'desc'    => esc_html__('Please add email subject.', 'peer-review-system'),
			'default' => esc_html__('New user account approval request at {{sitename}}', 'peer-review-system'),
		),
		array(
			'id'      => 'admin_email_address_verify_user',
			'type'    => 'text',
			'title'   => esc_html__('Admin Email', 'peer-review-system'),
			'desc'    => esc_html__('Please add admin email.', 'peer-review-system'),
			'default' => get_bloginfo('admin_email'),
		),
		array(
			'id'      => 'divider_adminemail_verify_user_confirmation_information',
			'desc'    =>	wp_kses(
				__(
					'{{name}} — To display the user name.<br>
								{{email}} — To display the user email.<br>
								{{sitename}} — To display the sitename.<br>
								{{login_url}} — To display the login url.<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      	=> 'email_admin_verify_user_registration_greeting',
			'type'    	=> 'text',
			'title'   	=>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    	=>  esc_html__('Please add text.', 'peer-review-system'),
			'default' 	=>  esc_html__('Hello,', 'peer-review-system'),
		),
		array(
			'id'        => 'admin_verify_user_registration_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('A new user “{{name}}” signed up with an email address “{{email}}”. <br /> Please login here to approve user account {{login_url}}.', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system'),
		),
		/* Admin Email on Register */
		array(
			'id'      => 'divider_email_admin_registration_templates',
			'type'    => 'info',
			'title'   => esc_html__('Admin email on new user registration', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'admin_registration_subject',
			'type'    => 'text',
			'title'   => esc_html__('Subject', 'peer-review-system'),
			'desc'    => esc_html__('Please add email subject.', 'peer-review-system'),
			'default' => esc_html__('New user registration at {{sitename}}', 'peer-review-system'),
		),
		array(
			'id'      => 'admin_email_address_registration',
			'type'    => 'text',
			'title'   => esc_html__('Admin Email', 'peer-review-system'),
			'desc'    => esc_html__('Please add admin email.', 'peer-review-system'),
			'default' => get_bloginfo('admin_email'),
		),
		array(
			'id'      => 'divider_adminemail_confirmation_information',
			'desc'    => wp_kses(
				__(
					'{{name}} — To display the user name.<br>
							{{email}} — To display the user email.<br>
							{{sitename}} — To display the sitename.<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      	=> 'email_admin_registration_greeting',
			'type'    	=> 'text',
			'title'   	=>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    	=>  esc_html__('Please add text.', 'peer-review-system'),
			'default' 	=>  esc_html__('Hello,', 'peer-review-system'),
		),
		array(
			'id'        => 'admin_registration_content',
			'type'      => 'textarea',
			'default'   => wp_kses(
				__('A new user “{{name}}” signed up with an email address “{{email}}”.', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),
	)
));


/* email Registration setting tab */
Redux::set_section($opt_name, array(
	'title'			=> esc_html__('Registration', 'peer-review-system'),
	'id'			=> 'registration_email_templates',
	'desc'			=> 'Registration email templates',
	'icon'			=> '',
	'subsection'	=> true,
	'fields'		=> array(

		/* Email to user on auto approve */
		array(
			'id'      => 'divider_user_register_auto_templates',
			'type'    => 'info',
			'title'   => esc_html__('Email on user registration with auto approve', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      	=> 'user_registration_auto_approve_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__('Subject', 'peer-review-system'),
			'desc'    	=> esc_html__('Please add subject for user registration.', 'peer-review-system'),
			'default' 	=> esc_html__('Thank you for signing up at {{sitename}}', 'peer-review-system'),
		),
		array(
			'id'      => 'register_user_auto_approve_info',
			'desc'    => wp_kses(
				__('{{name}} — To display the user name.<br>
						{{email}} — To display the email<br/>
						{{sitename}} — To display the sitename<br/>', 'peer-review-system'),
				array(
					'a'	=> array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
		),
		array(
			'id'      	=> 'email_user_registration_auto_approve_greeting',
			'type'    	=> 'text',
			'title'   	=> esc_html__('Greeting', 'peer-review-system'),
			'desc'    	=> esc_html__('Please add text for greeting.', 'peer-review-system'),
			'default' 	=> esc_html__('Hello {{name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'user_registration_auto_approve_content',
			'type'      => 'textarea',
			'default'   => wp_kses(
				__('Congratulations! <br /> You have successfully signed up at “{{sitename}}”.', 'peer-review-system'),
				array(
					'a'	=> array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email Contents', 'peer-review-system'),
		),

		/* Email to user on registration with verify by link */
		array(
			'id'      => 'divider_user_register_templates',
			'type'    => 'info',
			'title'   => esc_html__('Email on user registration with verification link', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      	=> 'user_registration_verify_link_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__('Subject', 'peer-review-system'),
			'desc'    	=> esc_html__('Please add subject for user registration.', 'peer-review-system'),
			'default' 	=> esc_html__('Thank you for signing up at {{sitename}}', 'peer-review-system'),
		),
		array(
			'id'      => 'register_user_verify_link_information',
			'desc'    => wp_kses(
				__('{{name}} — To display the user name.<br>
						{{email}} — To display the email<br/>
						{{sitename}} — To display the sitename<br/>
						{{password}} — To display the password<br/>
						{{verification_link}} — To display the verification link<br/>', 'peer-review-system'),
				array(
					'a'	=> array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
		),
		array(
			'id'      	=> 'email_user_registration_verify_link_greeting',
			'type'    	=> 'text',
			'title'   	=> esc_html__('Greeting', 'peer-review-system'),
			'desc'    	=> esc_html__('Please add text for greeting.', 'peer-review-system'),
			'default' 	=> esc_html__('Hello {{name}},', 'peer-review-system'),

		),
		array(
			'id'        => 'user_registration_verify_link_content',
			'type'      => 'textarea',
			'default'   => wp_kses(
				__('Thank you for signing up at “{{sitename}}”. <br />Please click the link below to verify your account <br /> {{verification_link}}.', 'peer-review-system'),
				array(
					'a'	=> array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email Contents', 'peer-review-system'),
		),
		/* Email on Password reset */
		array(
			'id'      => 'divider_password_reset_templates',
			'type'    => 'info',
			'title'   =>  esc_html__('Password Reset', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'user_password_reset_subject',
			'type'    => 'text',
			'title'   => esc_html__('Subject', 'peer-review-system'),
			'desc'    => esc_html__('Please add email subject.', 'peer-review-system'),
			'default' => esc_html__('Password reset request', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_user_reset_password_information',
			'desc'    => wp_kses(
				__('{{name}} — To display the user name.<br>
								{{email}} — To display the user email.<br>
								{{sitename}} — To display the sitename.<br>
								{{reset_link}} — To display the reset link.<br>', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'user_reset_password_greeting',
			'type'    => 'text',
			'title'   => esc_html__('Greeting', 'peer-review-system'),
			'desc'    => esc_html__('Please add text.', 'peer-review-system'),
			'default' => esc_html__('Hello {{name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'user_reset_password_content',
			'type'      => 'textarea',
			'default'   => wp_kses(
				__('Click on the link below to reset your password:<br />{{reset_link}}<br />Please be advised, If you don’t want to reset your password then please ignore this email and nothing will happen to your current password.<br />', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'		=> esc_html__('Email Content', 'peer-review-system'),
		),

		/* email to user on social registration */
		array(
			'id'      => 'divider_email_social_registration_templates',
			'type'    => 'info',
			'title'   => esc_html__('Google registration email', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'subject_social_registration_user_email',
			'type'    => 'text',
			'title'   => esc_html__('Subject', 'peer-review-system'),
			'desc'    => esc_html__('Please add email subject.', 'peer-review-system'),
			'default' => esc_html__('Signed up at {{sitename}} via google account', 'peer-review-system'),
		),
		array(
			'id'      => 'information_social_registration_user_email',
			'desc'    =>	wp_kses(
				__(
					'{{name}} — To display the user name.<br>
								{{email}} — To display the user email.<br>
								{{login_url}} — To display the login url.<br>
								{{sitename}} — To display the sitename.<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      	=> 'greeting_social_registration_user_email',
			'type'    	=> 'text',
			'title'   	=>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    	=>  esc_html__('Please add text.', 'peer-review-system'),
			'default' 	=>  esc_html__('Hello {{name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'content_social_registration_user_email',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('Thank you for the signing up at “{{sitename}}” Your account has been created successfully. ', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system'),
		),
		/* Email on Account Approve Request */
		array(
			'id'      => 'divider_approval_request_user_account_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('Account Approval Request', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'user_account_approval_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('Thank you for signing up at {{sitename}}', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_user_account_request_approval_information',
			'desc'    => wp_kses(
				__(
					'{{name}} — To display the user name.<br>
						{{email}} — To display the user email.<br>
						{{password}} — To display the user password.<br>
						{{sitename}} — To display the sitename.<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'user_account_approval_request_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'user_account_approval_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('Thank you for the signing up at {{sitename}}. Your account will be approved after the verification.', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),
		/* Email to user on Account Approved */
		array(
			'id'      => 'divider_approval_user_account_approved_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('User Account Approved', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'user_account_approved_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('Account approved', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_user_account_request_approval_information',
			'desc'    => wp_kses(
				__(
					'{{name}} — To display the user name.<br>
						{{email}} — To display the user email.<br>
						{{sitename}} — To display the sitename.<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'user_account_approved_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'user_account_approved_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('Congratulations! <br/> Your account has been approved by the admin.', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),
		/* Email to user on Account rejected */
		array(
			'id'      => 'divider_reject_user_account_templates',
			'type'    => 'info',
			'title'   => esc_html__('User Acoount rejected', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'user_mail_account_reject_subject',
			'type'    => 'text',
			'title'   => esc_html__('Subject', 'peer-review-system'),
			'desc'    => esc_html__('Please add email subject.', 'peer-review-system'),
			'default' => esc_html__('Account rejected', 'peer-review-system'),

		),
		array(
			'id'      => 'divider_user_account_reject_information',
			'desc'    => wp_kses(
				__(
					'{{name}} — To display the user name.<br>
					{{email}} — To display the user email.<br>
					{{sitename}} — To display the sitename.<br>',
					'peer-review-system'
				),
				array(
					'a'	=> array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
		),
		array(
			'id'      => 'user_email_account_reject_greeting',
			'type'    => 'text',
			'title'   => esc_html__('Greeting', 'peer-review-system'),
			'desc'    => esc_html__('Please add text.', 'peer-review-system'),
			'default' => esc_html__('Hello {{name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'reject_user_account_content',
			'type'      => 'textarea',
			'default'   => wp_kses(
				__('Your account has been rejected by the admin.', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email Contents', 'peer-review-system'),
		),
	),
));

/* email to Author on reviewer reply on article */
Redux::set_section($opt_name, array(
	'title'			=> esc_html__('Author', 'peer-review-system'),
	'id'			=> 'author_email_templates',
	'desc'			=> 'Author email templates',
	'icon'			=> '',
	'subsection'	=> true,
	'fields'		=> array(
		/* Email to author article submitted */
		array(
			'id'      => 'divider_author_submit_article_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('Article submitted', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'author_article_submit_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('Article submitted', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_reviewer_submit_article_information',
			'desc'    => wp_kses(
				__(
					'{{user_name}} — To display the author name.<br>
					{{article_name}} — To display the article name<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'author_submit_article_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{user_name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'author_submit_article_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('Congratulation your article has been submitted successfully!', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),

		/* Email to author on article approved */
		array(
			'id'      => 'divider_author_article_approved_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('Article approved', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'author_article_approved_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('Congratulations! Your article “{{article_name}}” has been approved', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_author_article_approved_information',
			'desc'    => wp_kses(
				__(
					'{{user_name}} — To display the author name.<br>
					{{article_name}} — To display the article name<br>
					{{article_link}} — To display the article link<br>
					{{editor_name}} — To display the editor name<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'author_article_approved_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{user_name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'author_article_approved_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('Congratulations!<br/> Your article with the name “{{article_name}}” has been approved. You can view your article here <br /> {{article_link}}', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),

		/* Email to author on publish edition */
		array(
			'id'      => 'divider_author_edition_publish_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('Edition published', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'author_edition_publish_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('Congratulations! Your edition “{{edition_name}}” has been published', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_author_edition_publish_information',
			'desc'    => wp_kses(
				__(
					'{{user_name}} — To display the author name.<br>
					{{article_names}} — To display the author name.<br>
					{{edition_link}} — To display the edition link.<br>
					{{edition_name}} — To display the edition name<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'author_edition_publish_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{user_name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'author_edition_publish_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('Congratulations!<br/>Your article with the name “{{article_names}}” has been published in the edition “{{edition_name}}”. You can view the edition here <br/>{{edition_link}}', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),

		/* Email to author on revision required */
		array(
			'id'      => 'divider_author_revision_required_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('Revision required', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'author_revision_required_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('Article revision required', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_author_revision_required_information',
			'desc'    => wp_kses(
				__(
					'{{user_name}} — To display the author name.<br>
					{{article_name}} — To display the article name.<br>
					{{editor_name}} — To display the editor name.<br>
					{{review_status}} — To display the review status.<br>
					{{feedback}} — To display the feedback<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'author_revision_required_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{user_name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'author_revision_required_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('Thank you for your submission <br/>Your article with the name “{{article_name}}” needs improvements. You can review it again and resubmit it after the changes <br/>Here is the feedback:<br/>{{feedback}}', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),

		/* Email to author on article rejected */
		array(
			'id'      => 'divider_author_article_rejected_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('Article rejected by the editor', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'author_article_rejected_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('Your article “{{article_name}}” has been rejected', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_author_article_rejected_information',
			'desc'    => wp_kses(
				__(
					'{{user_name}} — To display the author name.<br>
					{{editor_name}} — To display the editor name.<br>
					{{review_status}} — To display the review status.<br>
					{{review_comment}} — To display the review comments.<br>
					{{article_name}} — To display the article name<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'author_article_rejected_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{user_name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'author_article_rejected_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('Thank you for your submission<br/> Your article with the name “{{article_name}}” has been hard rejected and you can’t resubmit it.', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),
	),
));

/**
 * Email to Editor
 */
Redux::set_section($opt_name, array(
	'title'			=> esc_html__('Editor', 'peer-review-system'),
	'id'			=> 'editor_email_templates',
	'desc'			=> 'Editor email templates',
	'icon'			=> '',
	'subsection'	=> true,
	'fields'		=> array(
		/* Email to editor on reviewer commnet */
		array(
			'id'      => 'divider_reviewer_comment_editor_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('Reviewer comment to editor', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'reviewer_comment_editor_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('Reviewer comment on assigned article', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_reviewer_comment_editor_information',
			'desc'    => wp_kses(
				__(
					'{{user_name}} — To display the editor name.<br>
					{{article_name}} — To display the article name<br>
					{{review_status}} — To display the review status<br>
					{{reviewer_name}} — To display the reviewer name<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'reviewer_comment_editor_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{user_name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'reviewer_comment_editor_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('The review {{reviewer_name}} has been completed the review on the article "{{article_name}}" and has left some comments with the status “{{review_status}}”', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),

		/* Email to editor on author commnet */
		array(
			'id'      => 'divider_author_comment_editor_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('Author comment to editor', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'author_comment_editor_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('Author comment on {{article_name}}', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_author_comment_editor_information',
			'desc'    => wp_kses(
				__(
					'{{user_name}} — To display the editor name.<br>
					{{article_name}} — To display the article name<br>
					{{author_comment}} — To display the review status<br>
					{{author_name}} — To display the article author name<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'author_comment_editor_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{user_name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'author_comment_editor_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('The author {{author_name}} send the comment on the article "{{article_name}}" and has left some comments “{{author_comment}}”', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),

		/* New article posted */
		array(
			'id'      => 'divider_editor_new_article_posted_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('New Article Posted', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'editor_new_article_posted_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('A new article has been received for review', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_editor_new_article_posted_information',
			'desc'    => wp_kses(
				__(
					'{{user_name}} — To display the editor name.<br>
					{{article_name}} — To display the article name<br>
					{{author_name}} — To display the author name<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'editor_new_article_posted_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{user_name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'editor_new_article_posted_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('You have received a new article "{{article_name}}" from "{{author_name}}", your review is required on this article', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),

		/* Article resubmission by the author */
		array(
			'id'      => 'divider_editor_article_resubmit_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('Article resubmitted', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'editor_article_resubmit_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('Article has been resubmitted for review', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_editor_article_resubmit_information',
			'desc'    => wp_kses(
				__(
					'{{user_name}} — To display the editor name.<br>
					{{article_name}} — To display the article name<br>
					{{author_name}} — To display the author name<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'editor_article_resubmit_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{user_name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'editor_article_resubmit_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('The author {{author_name}} has resubmitted the article “{{article_name}}”. You can review it and provide your review on this', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),
	),
));

/**
 * Email to Reviewer
 */
Redux::set_section($opt_name, array(
	'title'			=> esc_html__('Reviewer', 'peer-review-system'),
	'id'			=> 'reviewer_email_templates',
	'desc'			=> 'Reviewer email templates',
	'icon'			=> '',
	'subsection'	=> true,
	'fields'		=> array(
		/* Email to reviewer on assign article by editor */
		array(
			'id'      => 'divider_reviewer_assigned_article_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('Article assign to reviewer', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'reviewer_assigned_article_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('Your review required on the article', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_reviewer_assigned_article_information',
			'desc'    => wp_kses(
				__(
					'{{user_name}} — To display the reviewer name.<br>
					{{article_name}} — To display the article name<br>
					{{editor_name}} — To display the editor name<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'reviewer_assigned_article_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{user_name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'reviewer_assigned_article_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('You have received a new article "{{article_name}}" from "{{editor_name}}" for review, you can review and leave your comments on it', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),

		/* Further review required */
		array(
			'id'      => 'divider_reviewer_further_review_templates',
			'type'    => 'info',
			'title'   => 	esc_html__('Comment for further review', 'peer-review-system'),
			'style'   => 'info',
		),
		array(
			'id'      => 'reviewer_further_review_subject',
			'type'    => 'text',
			'title'   =>  esc_html__('Subject', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add email subject.', 'peer-review-system'),
			'default' =>  esc_html__('Further review required', 'peer-review-system'),
		),
		array(
			'id'      => 'divider_reviewer_further_review_information',
			'desc'    => wp_kses(
				__(
					'{{user_name}} — To display the reviewer name.<br>
					{{article_name}} — To display the article name<br>
					{{editor_name}} — To display the reviewer name<br>',
					'peer-review-system'
				),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     => esc_html__('Email setting variables', 'peer-review-system'),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle'
		),
		array(
			'id'      => 'reviewer_further_review_greeting',
			'type'    => 'text',
			'title'   =>  esc_html__('Greeting', 'peer-review-system'),
			'desc'    =>  esc_html__('Please add text.', 'peer-review-system'),
			'default' =>  esc_html__('Hello {{user_name}},', 'peer-review-system'),
		),
		array(
			'id'        => 'reviewer_further_review_content',
			'type'      => 'textarea',
			'default'   =>  wp_kses(
				__('The editor “{{editor_name}}” has requested to review the article “{{article_name}}” again. You can review the article and leave your comments with the status', 'peer-review-system'),
				array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				)
			),
			'title'     =>  esc_html__('Email Contents', 'peer-review-system')
		),
	),
));
