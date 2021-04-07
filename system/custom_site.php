<?php
	$arr_og = array(
		"og_title" => "ดันยอดแคมเปญด้วย Taximail - ส่งอีเมล์มาร์เก็ตติ้งด้วยซอฟต์แวร์การตลาดผ่านอีเมล์",
		"og_description" => "Taximail คือซอฟต์แวร์ช่วยทำการตลาดผ่านอีเมล์ เป็นเครื่องมือที่ทำให้การทำการตลาดด้วยอีเมลเป็นเรื่องง่าย ทั้งการส่งอีเมลแคมเปญ และอีเมลธุรกรรมต่างๆ ช่วยให้ส่งได้รวดเร็ว และวัดผลได้ชัดเจน พร้อมทั้งรองรับการเชื่อมต่อกับระบบต่างๆ",
		"og_image" => PATH_IMAGE."layout/share_fb.jpg"
		);
	function TrimWithDot($data /*String*/, $len /*Integer*/ = 100 , /*String */ $end = "..."){
		return mb_strimwidth($data, 0, $len, $end);
	}
	function month2TH($text,$abbr=false){
		$patt = array();
		$patt[1] = '/JANUARY/';
		$patt[2] = '/FEBRUARY/';
		$patt[3] = '/MARCH/';
		$patt[4] = '/APRIL/';
		$patt[5] = '/MAY/';
		$patt[6] = '/JUNE/';
		$patt[7] = '/JULY/';
		$patt[8] = '/AUGUST/';
		$patt[9] = '/SEPTEMBER/';
		$patt[10] = '/OCTOBER/';
		$patt[11] = '/NOVEMBER/';
		$patt[12] = '/DECEMBER/';

		$patt_abbr = array();
		$patt_abbr[1] = '/JAN/';
		$patt_abbr[2] = '/FEB/';
		$patt_abbr[3] = '/MAR/';
		$patt_abbr[4] = '/APR/';
		$patt_abbr[5] = '/MAY/';
		$patt_abbr[6] = '/JUN/';
		$patt_abbr[7] = '/JUL/';
		$patt_abbr[8] = '/AUG/';
		$patt_abbr[9] = '/SEP/';
		$patt_abbr[10] = '/OCT/';
		$patt_abbr[11] = '/NOV/';
		$patt_abbr[12] = '/DEC/';

		$repl = array();
		if($abbr){
			$repl[1]='ม.ค.';
			$repl[2]='ก.พ.';
			$repl[3]='มี.ค.';
			$repl[4]='เม.ย.';
			$repl[5]='พ.ค.';
			$repl[6]='มิ.ย.';
			$repl[7]='ก.ค.';
			$repl[8]='ส.ค.';
			$repl[9]='ก.ย.';
			$repl[10]='ต.ค.';
			$repl[11]='พ.ย.';
			$repl[12]='ธ.ค.';
		}else{
			$repl[1] = 'มกราคม';
			$repl[2] = 'กุมภาพันธ์';
			$repl[3] = 'มีนาคม';
			$repl[4] = 'เมษายน';
			$repl[5] = 'พฤษภาคม';
			$repl[6] = 'มิถุนายน';
			$repl[7] = 'กรกฏาคม';
			$repl[8] = 'สิงหาคม';
			$repl[9] = 'กันยายน';
			$repl[10] = 'ตุลาคม';
			$repl[11] = 'พฤศจิกายน';
			$repl[12] = 'ธันวาคม';
		}
		$text = strtoupper($text);
		$text = preg_replace($patt,$repl,$text);
		$text = preg_replace($patt_abbr,$repl,$text);
		return $text;
	}
	function genDateThumb($text_date){
		$patt = array();
		$patt[1] = '/JANUARY/';
		$patt[2] = '/FEBRUARY/';
		$patt[3] = '/MARCH/';
		$patt[4] = '/APRIL/';
		$patt[5] = '/MAY/';
		$patt[6] = '/JUNE/';
		$patt[7] = '/JULY/';
		$patt[8] = '/AUGUST/';
		$patt[9] = '/SEPTEMBER/';
		$patt[10] = '/OCTOBER/';
		$patt[11] = '/NOVEMBER/';
		$patt[12] = '/DECEMBER/';

		$repl = array();
		$repl[1] = 'มกราคม';
		$repl[2] = 'กุมภาพันธ์';
		$repl[3] = 'มีนาคม';
		$repl[4] = 'เมษายน';
		$repl[5] = 'พฤษภาคม';
		$repl[6] = 'มิถุนายน';
		$repl[7] = 'กรกฏาคม';
		$repl[8] = 'สิงหาคม';
		$repl[9] = 'กันยายน';
		$repl[10] = 'ตุลาคม';
		$repl[11] = 'พฤศจิกายน';
		$repl[12] = 'ธันวาคม';

		$text = strtoupper($text_date);
		if(LANG == "tha"){
			$text = preg_replace($patt,$repl,$text);
			$arr_dd = explode(", ", $text);
			$arr_dd[1] = $arr_dd[1]+543;
			$text = $arr_dd[0].", ".$arr_dd[1];
		}
		return $text;
	}
	function switchLanguage($special = "",$linkpage ="",$langpage="") {
		$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		if (isset($_SERVER["HTTP_X_FORWARDED_PROTO"])) {
			$pageURL = $_SERVER["HTTP_X_FORWARDED_PROTO"]."://";
		}
		if ($_SERVER["SERVER_PORT"] != "80"){
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}else{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		if ($special == "") {
		} else {
			// if(LANG == "th"){
			// 	$pageURL
			// }else{

			// }
			// $pageURL
			// return $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			// $s_lang = explode("/", $_SERVER["REQUEST_URI"]);
			// $s_lang = "/".$s_lang[1]."/";
			// if ($_SERVER["SERVER_PORT"] != "80"){
			// 	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$s_lang."location/".$special;
			// }else{
			// 	$pageURL .= $_SERVER["SERVER_NAME"].$s_lang."location/".$special;
			// }
		}
		if($special != ""){
			// $reg = $special;
			if($special == "th"){
				
				if($linkpage != "" && $langpage == "THA"){
					return WEB_META_BASE_URL.$special."/".$linkpage;
				}else{
					if($_SERVER["REQUEST_URI"] == "/"){
						return WEB_META_BASE_URL."th/home";
					}
				}
				return  str_replace('/en/', '/th/' , $pageURL);
			}else if($special == "en"){
				if($linkpage != "" && $langpage == "ENG"){
					return WEB_META_BASE_URL.$special."/".$linkpage;
				}else{
					if($_SERVER["REQUEST_URI"] == "/"){
						return WEB_META_BASE_URL."en/home";
					}
				}
				return  str_replace('/th/', '/en/' , $pageURL);
			}
		}

		$pos = strpos($pageURL, '/th/');
		if($pos === false){
			return  str_replace('/en/', '/th/' , $pageURL);
		}
		return  str_replace('/th/', '/en/' , $pageURL);
	}
	function sendEmailAdmin($tomail,$subject,$body,$headet_type,$use_template="",$label=array()){
		$result = array();
		$result["send"] = true;

		$cf_data = genEmailContent($body,$label);

		$all_cf = $cf_data['cf'];

		if (isset($all_cf["CF_body_email"])) {
			$body_text = $all_cf["CF_body_email"];
			$all_cf["CF_body_plain_text"] = strip_tags($body_text);
	 	}

		$mail = new OMMail();
		$mail->Sender =  EMAIL_SENDER;
		$mail->From = EMAIL_SENDER ;
		$mail->FromName = MAIL_SENDER_NAME;

		$mail->IsHTML(true);
		$mail->SMTPSecure = "tls";
		$mail->AuthType = "CRAM-MD5";
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = 'base64';
		
		$mail->AddAddress($tomail);
		$mail->Subject = $subject;
		$mail->addCustomHeader(HEADER_TAXI_ifirstfix.$headet_type);
		$mail->addReplyTo(SYSTEM_REPLY_EMAIL,SYSTEM_REPLY_NAME);
		$mail->addCustomHeader("X-Taximail-Template-Key: ".TEMPLATE_CONTACT_FORM);
		$mail->Body = json_encode($all_cf);

		if (!$mail->sendMail()) {
			$result["msg"] = $mail->ErrorInfo;
			$result["send"] = false;
		}
		return $result;
	}

	function wrapBodyTemplate($body,$t_id,$email){
		if($t_id == ""){
			$t_id = 0;
		}
	    $txtBody = array();
		$txtBody = [
			'ขอบคุณที่ให้ความสนใจในบริการของเรา'
			,'เราจะติดต่อคุณกลับไปในไม่เกิน 24 ชั่วโมงจากนี้'

			,'เพื่อเป็นการรู้จักคุณได้มากขึ้น เราอยากถามคุณว่า'
			,'คุณได้ใช้บริการ Email marketing รายอื่นหรือจัดส่งด้วยตนเองอยู่หรือไม่?'
			,'ไม่, ฉันไม่ได้ใช้'

			,'กรณีที่ใช้อยู่ คุณมีปัญหาหรือข้อติดขัดอะไรมากที่สุด?'
			,'ส่งออกได้ช้ามาก'
			,'ส่งแล้วเข้า SPAM folder'
			,'ต้องการความช่วยเหลือเป็นภาษาไทย'
			,'ค่าบริการสูงเกินไป'
			,'อื่นๆ'
		];

		if(LANG == 'eng'){
			$txtBody = [
				'Thank you for your interest in our services.'
				,'We will be in touch within 24 hours.'

				,'Please take a few minutes to let us know about your Email Marketing experience.'
				,'Are you using any service provider or manually generate from your organization?'
				,'No, I am not'

				,'If yes, from your experience what do you find the most frustrating about the service?'
				,'Low sending speed'
				,'Emails get marked as SPAM'
				,'Prefer Thai speaking customer service'
				,'Pricing'
				,'Other'
			];
		}
		$cf_data = array();
			$cf_data["CF_link1"] =  WEB_META_BASE_LANG.'thank/'.rawurlencode($txtBody[4]).'/'.$t_id;
			$cf_data["CF_link2"] =  WEB_META_BASE_LANG.'thank/'.rawurlencode($txtBody[6]).'/'.$t_id;
			$cf_data["CF_link3"] =  WEB_META_BASE_LANG.'thank/'.rawurlencode($txtBody[7]).'/'.$t_id;
			$cf_data["CF_link4"] =  WEB_META_BASE_LANG.'thank/'.rawurlencode($txtBody[8]).'/'.$t_id;
			$cf_data["CF_link5"] =  WEB_META_BASE_LANG.'thank/'.rawurlencode($txtBody[9]).'/'.$t_id;
			$cf_data["CF_link6"] =  WEB_META_BASE_LANG.'thank/'.rawurlencode($txtBody[10]).'/'.$t_id;
			$cf_data["CF_year"] =  date("Y");
			$cf_data["CF_mailto"] =  $email;

		return $cf_data;
		$style_tag_a = 'color: #888;text-decoration: initial;';
		$style_btn = 'background: #f3f3f3;padding: 10px 5px;border-radius: 5px;color: #fff;text-align: center;text-decoration: none;color:#555;border:1px solid #555;';
		$style_wrap_btn = "padding-top: 5px;width: 300px;margin:0 auto;";

		$bodyHtml = '';
			
		$bodyHtml .= '';
		$bodyHtml .= '<div class="content ctn" style="font-size: 16px;color: #000;text-align: center;max-width: 600px;padding: 20px;min-width:470px;margin: 0 auto;">';
			$bodyHtml .= '<div class="wrap-img-tag taxi-logo" style="text-align: right;">';
				$bodyHtml .= '<img align="center" src="'.trim(WEB_META_BASE_URL).'images/layout/taximail-logo-email.png" alt="taximail">';
			$bodyHtml .= '</div>';
			
			$bodyHtml .= '<div class="thank-topic" style="padding-top:50px;font-size: 24px;text-align: center;">'.$txtBody[0].'</div>';
			$bodyHtml .= '<div class="thank-topic-sub" style="padding-bottom:30px;font-size: 16px;text-align:center;">'.$txtBody[1].'</div>';
			$bodyHtml .= '<br><div style="margin:8px 0;height:1px;background-color:#ddd;width;100%;" class="line-1"></div>';
			$bodyHtml .= '<br>'.$txtBody[2];
			$bodyHtml .= '<br>'.$txtBody[3];
			
			$bodyHtml .= '<table style="border-style: solid; border-width: 0pt;" border="0" cellspacing="0" cellpadding="0" width="100%" frame="none"><tr><td align="center">';
			$bodyHtml .= '<div class="wrap-btn" style="'.$style_wrap_btn.'">';
				$bodyHtml .= '<a style="'.$style_tag_a.'" href="'.trim(WEB_META_BASE_LANG).'thank/'.rawurlencode($txtBody[4]).'/'.$t_id.'">';
					$bodyHtml .= '<div style="'.$style_btn.'" class="btn">';
						$bodyHtml .= $txtBody[4];
					$bodyHtml .= '</div>';
				$bodyHtml .= '</a>';
			$bodyHtml .= '</div>';
			$bodyHtml .= '</td></tr></table>';

			
			$bodyHtml .= '<br>'.$txtBody[5];
			// $bodyHtml .= '<br><a style="'.$style_tag_a.'" href="'.WEB_META_BASE_URL.LANG.'/thank/'.rawurlencode('ส่งออกได้ช้ามาก').'/'.$t_id.'">ส่งออกได้ช้ามาก</a>';
			// $bodyHtml .= '<br><a style="'.$style_tag_a.'" href="'.WEB_META_BASE_URL.LANG.'/thank/'.rawurlencode('ส่งแล้วเข้า SPAM folder').'/'.$t_id.'">ส่งแล้วเข้า SPAM folder</a>';
			// $bodyHtml .= '<br><a style="'.$style_tag_a.'" href="'.WEB_META_BASE_URL.LANG.'/thank/'.rawurlencode('ต้องการความช่วยเหลือเป็นภาษาไทย').'/'.$t_id.'">ต้องการความช่วยเหลือเป็นภาษาไทย</a>';
			// $bodyHtml .= '<br><a style="'.$style_tag_a.'" href="'.WEB_META_BASE_URL.LANG.'/thank/'.rawurlencode('ค่าบริการสูงเกินไป').'/'.$t_id.'">ค่าบริการสูงเกินไป</a>';
			// $bodyHtml .= '<br><a style="'.$style_tag_a.'" href="'.WEB_META_BASE_URL.LANG.'/thank/'.rawurlencode('อื่นๆ').'/'.$t_id.'">[อื่นๆ]</a>';

			$bodyHtml .= '<table style="border-style: solid; border-width: 0pt;" border="0" cellspacing="0" cellpadding="0" width="100%" frame="none"><tr><td align="center">';
			$bodyHtml .= '<div class="wrap-btn" style="'.$style_wrap_btn.'">';
				$bodyHtml .= '<a style="'.$style_tag_a.'" href="'.trim(WEB_META_BASE_LANG).'thank/'.rawurlencode($txtBody[6]).'/'.$t_id.'">';
					$bodyHtml .= '<div style="'.$style_btn.'" class="btn">';
						$bodyHtml .= $txtBody[6];
					$bodyHtml .= '</div>';
				$bodyHtml .= '</a>';
			$bodyHtml .= '</div>';
			$bodyHtml .= '</td></tr></table>';

			$bodyHtml .= '<table style="border-style: solid; border-width: 0pt;" border="0" cellspacing="0" cellpadding="0" width="100%" frame="none"><tr><td align="center">';
			$bodyHtml .= '<div class="wrap-btn" style="'.$style_wrap_btn.'">';
				$bodyHtml .= '<a style="'.$style_tag_a.'" href="'.trim(WEB_META_BASE_LANG).'thank/'.rawurlencode($txtBody[7]).'/'.$t_id.'">';
					$bodyHtml .= '<div style="'.$style_btn.'" class="btn">';
						$bodyHtml .= $txtBody[7];
					$bodyHtml .= '</div>';
				$bodyHtml .= '</a>';
			$bodyHtml .= '</div>';
			$bodyHtml .= '</td></tr></table>';

			$bodyHtml .= '<table style="border-style: solid; border-width: 0pt;" border="0" cellspacing="0" cellpadding="0" width="100%" frame="none"><tr><td align="center">';
			$bodyHtml .= '<div class="wrap-btn" style="'.$style_wrap_btn.'">';
				$bodyHtml .= '<a style="'.$style_tag_a.'" href="'.trim(WEB_META_BASE_LANG).'thank/'.rawurlencode($txtBody[8]).'/'.$t_id.'">';
					$bodyHtml .= '<div style="'.$style_btn.'" class="btn">';
						$bodyHtml .= $txtBody[8];
					$bodyHtml .= '</div>';
				$bodyHtml .= '</a>';
			$bodyHtml .= '</div>';
			$bodyHtml .= '</td></tr></table>';

			$bodyHtml .= '<table style="border-style: solid; border-width: 0pt;" border="0" cellspacing="0" cellpadding="0" width="100%" frame="none"><tr><td align="center">';
			$bodyHtml .= '<div class="wrap-btn" style="'.$style_wrap_btn.'">';
				$bodyHtml .= '<a style="'.$style_tag_a.'" href="'.trim(WEB_META_BASE_LANG).'thank/'.rawurlencode($txtBody[9]).'/'.$t_id.'">';
					$bodyHtml .= '<div style="'.$style_btn.'" class="btn">';
						$bodyHtml .= $txtBody[9];
					$bodyHtml .= '</div>';
				$bodyHtml .= '</a>';
			$bodyHtml .= '</div>';
			$bodyHtml .= '</td></tr></table>';

			$bodyHtml .= '<table style="border-style: solid; border-width: 0pt;" border="0" cellspacing="0" cellpadding="0" width="100%" frame="none"><tr><td align="center">';
			$bodyHtml .= '<div class="wrap-btn" style="'.$style_wrap_btn.'">';
				$bodyHtml .= '<a style="'.$style_tag_a.'" href="'.trim(WEB_META_BASE_LANG).'thank/'.rawurlencode($txtBody[10]).'/'.$t_id.'">';
					$bodyHtml .= '<div style="'.$style_btn.'" class="btn">';
						$bodyHtml .= $txtBody[10];
					$bodyHtml .= '</div>';
				$bodyHtml .= '</a>';
			$bodyHtml .= '</div>';
			$bodyHtml .= '</td></tr></table>';

			$bodyHtml .= '<br>';
			$bodyHtml .= '<br>';
			$bodyHtml .= '<br>';

			$bodyHtml .= '<div class="wrap-img-tag ifirstfix" style="font-size: 10px;color: #aaa;text-align: center;padding-top: 15px;background-image: url(\''.trim(WEB_META_BASE_URL).'images/layout/mail_bg.png\');background-repeat: no-repeat;background-position: center top;">';
				$bodyHtml .= '<img style="padding-bottom: 10px;padding-top: 10px;height: 14px;width: auto;" align="center" src="'.trim(WEB_META_BASE_URL).'images/layout/ifirstfix-logo-email.png" alt="ifirstfix">';
				$bodyHtml .= '<br>';
				$bodyHtml .= '<div>© 2016 ifirstfix Technology Co., Ltd.</div>';
			$bodyHtml .= '</div>';


		return  $bodyHtml .= '</div>';
  	}
  	function genBodyNotify($valInc,$mode = "",$label = array(),$title_text='ข้อมูลผู้ลงทะเบียน'){

  		if( $mode == "custom" ){
  			$valAll = $valInc;
  		}else{
	  		$valAll = array();
			$valAll['receiver'] = !empty($valInc['receiver']) ? $valInc['receiver'] : '';
			$valAll['full_name'] = !empty($valInc['full_name']) ? $valInc['full_name'] : '';
			$valAll['tel'] = !empty($valInc['tel']) ? $valInc['tel'] : '';
			$valAll['company'] = !empty($valInc['company']) ? $valInc['company'] : '';
			// $valAll['remark'] = !empty($valInc['remark']) ? $valInc['remark'] : '';
			$valAll['answer'] = !empty($valInc['answer']) ? $valInc['answer'] : '-';
			$label = ['อีเมล', 'ชื่อ-สกุล', 'เบอร์โทรศัพท์', 'บริษัท', 'คำตอบ'];
  		}

		$bodyHtml = '';


		$bodyHtml .= '<div class="content ctn" style="font-size: 14px;color: #000;text-align: left;max-width: 600px;min-width:420px;padding: 20px;margin: 0 auto;">';

			$bodyHtml .= '<div class="wrap-img-tag taxi-logo" style="text-align: right;">';
				$bodyHtml .= '<img align="center" src="'.trim(WEB_META_BASE_URL).'images/layout/taximail-logo-email.png" alt="taximail">';
			$bodyHtml .= '</div>';

			$bodyHtml .= '<br><span class="topic" style="color: #000;font-size: 18px;font-weight: bold;margin: 0 0 10px;display: inline-block;">'.$title_text.'</span>';

			$loopDetail = 0;
			foreach ($valAll as $key => $val) {
				$bodyHtml .= '<br><span class="topic-sub" style="padding-left: 15px;">';
					$bodyHtml .= $label[$loopDetail];
				$bodyHtml .= '</span>: <span class="detail-sub">';
					$bodyHtml .= $val;
				$bodyHtml .= '</span>';

				$loopDetail++;
			}

			$bodyHtml .= '<br>';
			$bodyHtml .= '<br>';	

			$bodyHtml .= '<div class="wrap-img-tag ifirstfix" style="font-size: 10px;color: #aaa;text-align: center;padding-top: 15px;background-image: url(\''.trim(WEB_META_BASE_URL).'images/layout/mail_bg.png\');background-repeat: no-repeat;background-position: center top;">';
				$bodyHtml .= '<img style="padding-bottom: 10px;padding-top: 10px;height: 14px;width: auto;" align="center" src="'.trim(WEB_META_BASE_URL).'images/layout/ifirstfix-logo-email.png" alt="ifirstfix">';
				$bodyHtml .= '<br>';
				$bodyHtml .= '<div>© 2016 ifirstfix Technology Co., Ltd.</div>';
			$bodyHtml .= '</div>';

		return $bodyHtml .= '</div>';
  	}

  	function genEmailContent($cf,$label){
  		$response = array();
  		$response['cf'] = array();

  		$style_table = 'style="border: 1px solid #ccc; border-collapse: collapse;margin: 0 auto;min-width:400px;"';
        $style_cell = 'style="border: 1px solid #ccc;padding:4px 8px;text-align:left;vertical-align: top !important;"';

  		$html = '';
  		$html .= '<h3 style="margin-bottom:20px;text-align:center;">'.$cf['CF_topic'].'</h3>';
  		$html .= '<table '.$style_table .'>';
  		$html .= '	<tbody>';
  		unset($cf['CF_topic']);
  		$i = 0;
  		foreach ($cf as $key => $value) {
  			$title = isset($label[$i]) ? $label[$i] : '';

  			$html .= '<tr>';
  			$html .= '	<td '.$style_cell.'>'.$title.'</td>';
  			$html .= '	<td '.$style_cell.'>'.$value.'</td>';
  			$html .= '</tr>';
  			$i++;
  		}

  		$html .= '	</tbody>';
  		$html .= '</table>';
  		$response['cf']['CF_body_email'] = $html;

  		return $response;

  	}

  	function replyMailContact($mail_subject,$form_custom,$type,$config_name,$message,$mail_type,$reply_email=null){
  		$config_name = 'reply_contact_technical_support_template_en';
  		$curl_param = array();
  		$curl_param['Command'] = 'Ticket.GetMailTemplate';
  		$curl_param['ConfigName'] = $config_name;

  		$response = cURL_API($curl_param);
        $result = json_decode($response,true);

        if(isset($result['Success']) && $result['Success'] == true){
        	$data = $result['data'];
        	$case_id = $data['case_id'];

        	if($case_id != -1){
	        	$cf = array();
	        	$cf['CF_name'] = $form_custom['CF_name'];
	        	$cf['CF_case_id'] = $case_id;
	        	$cf['CF_case_subject'] = $mail_subject;
	        	$cf['CF_message'] = $message;

	        	$data['cf'] = $cf;
	        	$data["to_email"] = $form_custom['CF_email'];
	        	$data["to_name"] = $form_custom['CF_name'];

	        	$enable_custom_header = true;

				$cf_data = $data["cf"];
				$all_cf = $cf_data;

				$mail = new OMMail();
				$mail->IsHTML = true;
				$mail->AuthType = "CRAM-MD5";
				$mail->CharSet = 'UTF-8';
				$mail->Encoding = 'base64';
				$mail->addAddress($data["to_email"], $data["to_name"]); 
				$mail->From = EMAIL_SENDER;
				$mail->FromName = MAIL_SENDER_NAME;

				if(isset($reply_email)){
					$mail->addReplyTo($reply_email,SYSTEM_REPLY_NAME);
				}else{
					$mail->addReplyTo(SYSTEM_REPLY_EMAIL,SYSTEM_REPLY_NAME);
				}
				
				$mail->Subject = $data["subject"];
				$mail->AltBody = "No content plain";
				// $mail->SMTPDebug = true;

				if (isset($all_cf["CF_body_email"])) {
					$body_text = $all_cf["CF_body_email"];
					$plain = strip_tags($body_text);
					if ($plain == "") {
						$plain = $body_text;
					}
					$all_cf["CF_body_plain_text"] = $plain;
					$mail->AltBody = $plain;
			 	}

				$mail->Body = json_encode($all_cf);

				if ($enable_custom_header) {
					$mail->addCustomHeader("X-Taximail-Template-Key: ".$data["template_key"]);
					if (isset($data["trnsc_group"])) {
						$mail->addCustomHeader("X-Transactional-Group: ".$data["trnsc_group"]);
					}
					if (isset($data["bcc"])) {
						$mail->addCustomHeader("X-TM-BCC: ".$data["bcc"]);
					}
				}

				$r = $mail->send();

				if($r == true){
					$curl_param = array();
					$curl_param['Command'] = 'Ticket.UpdateUserData';
					$curl_param['Subject'] = $mail_subject;
					$curl_param['Message'] = $message;
					$curl_param['CaseID'] = $case_id;
					$curl_param['MailType'] = $mail_type;
					$curl_param['CustomerName'] = $data["to_name"];
					$curl_param['CustomerEmail'] = $data["to_email"];
					$curl_param['RawData'] = json_encode($form_custom);
					$res = cURL_API($curl_param);
				}

				return $r;
			}

        }
  	}

?>