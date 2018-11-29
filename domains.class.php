<?PHP
class Domains{

	public $foundRows = 0;
	
	public function getAll($domain_status) {


		switch($domain_status){
			case 'trash':
				$status = 'trash';
				break;
			case 'archive':
				$status = 'archive';
				break;
			default:
				$status = 'active';
				break;
		}


		$WHERE = " WHERE `domain_status` = '".sql_quote($status)."'";

		$PDO = PDO_CONNECT();

		//$command = "SELECT * FROM `domains` ORDER BY `created_date`";

		$command ="SELECT domains.*, domains_google.google_status, domains_google.google_ppc_manager, domains_bing.bing_status, domains_bing.bing_ppc_manager, domains_facebook.facebook_ppc_manager, domains_facebook.facebook_status FROM domains LEFT OUTER JOIN domains_google ON domains.domain = domains_google.domain LEFT OUTER JOIN domains_bing ON domains.domain = domains_bing.domain LEFT OUTER JOIN domains_facebook ON domains.domain = domains_facebook.domain $WHERE"; 


		$result = $PDO->prepare($command);
		$result->execute();


		if ($result->rowCount() == 0) {
			return false;
		}

		$return = array();
		while ($d = $result->fetch(PDO::FETCH_ASSOC)) {
			

			if (strpos($d['lang'], ',') !== false) {
				
			    $d['lang'] = explode(',', $d['lang']);
			

			}

			$return[] = $d;

		}

		$this->foundRows = $result->rowCount();


	//	cln_print_r($return);

	
		return $return;
	}

	public function get($domain) {

		$PDO = PDO_CONNECT();

		$command = "SELECT * FROM `domains` WHERE `domain` = '".sql_quote($domain)."' LIMIT 1";
		$result = $PDO->prepare($command);
		$result->execute();

		$return = array();
		while ($d = $result->fetch(PDO::FETCH_ASSOC)) {
			$return[] = $d;
		}

		return $return;
	}


	public function changeDomainStatus() {

		$setQuery = "";
		$setQuery .= " `domain_status` = '".sql_quote($_POST['status'])."' ";

		$PDO = PDO_CONNECT();

		$command = "UPDATE `domains` SET $setQuery WHERE `id` = '".sql_quote($_POST['id'])."' LIMIT 1 ";

		$result = $PDO->prepare($command);
		$result->execute();
		
		return true;
	
	}	


	public function add() {
		
		if (isset($_POST['domain']) && $_POST['domain'] == ''){
			$error['domain'] = 'Please enter a domain name.';
		}
				
		if(isset($error)) {
			return $error;
		}
		
		$PDO = PDO_CONNECT();

		$siteName = preg_replace('#^https?://#', '', $_POST['domain']);  // remove protoocol
		$siteName = str_replace('www.', '', $siteName); // remove www.
		$siteName = strtolower($siteName);


		$command = "
			INSERT INTO `domains` (
				`domain`,
				`hosting`,
				`hosting_version`,
				`created_date`,
				`created_by`,
				`website_dev`,
				`website_ready`,
				`site_type`,
				`lang`,
				`wp_login`,
				`ftp_user`,
				`ftp_password`,
				`ftp_port`,
				`notes`
			)
			VALUES (
				'".sql_quote($siteName)."',
				'".sql_quote($_POST['hosting'])."',
				'".sql_quote($_POST['hosting_version'])."',
				'".sql_quote($_POST['created_date'])."',
				'".sql_quote($_POST['created_by'])."',
				'".sql_quote($_POST['website_dev'])."',
				'".sql_quote($_POST['website_ready'])."',
				'".sql_quote($_POST['site_type'])."',
				'".sql_quote($_POST['lang'])."',
				'".sql_quote($_POST['wp_login'])."',
				'".sql_quote($_POST['ftp_user'])."',
				'".sql_quote($_POST['ftp_password'])."',
				'".sql_quote($_POST['ftp_port'])."',
				'".sql_quote($_POST['notes'])."'				
			)
		";

		$result = $PDO->prepare($command);
		$result->execute();

		return true;
	}


	public function edit($data, $domain) {

		if(isset($error)) {
			return $error;
		}
		
		$setQuery = "";
		$setQuery .= " `notes` = '".sql_quote($_POST['domain_notes'])."' ";
	//	$setQuery .= " , `email` = '".sql_quote($_POST['email'])."' ";


		$PDO = PDO_CONNECT();

		$command = "UPDATE `domains` SET $setQuery WHERE `domain` = '".sql_quote($domain)."' LIMIT 1 ";
		$result = $PDO->prepare($command);
		$result->execute();
		
		return true;
	}


	public function delete($domain) {

		$PDO = PDO_CONNECT();

		$command = "DELETE FROM `domains` WHERE domain = '".sql_quote($domain)."'";
		$result = $PDO->prepare($command);
		$result->execute();
		return true;
	}


}