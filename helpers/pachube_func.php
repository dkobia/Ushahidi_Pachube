<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Pachube helper class.
 *
 * @package	   Pachube
 * @author	   Ushahidi Team
 * @copyright  (c) 2008 Ushahidi Team
 * @license	   http://www.ushahidi.com/license.html
 */
class pachube_func_Core {

	public static function condition($condition = FALSE)
	{
		switch ($condition) {
			case 'gt':
				return 'goes >';
				break;
			case 'gte':
				return 'goes >=';
				break;
			case 'lt':
				return 'goes <';
				break;
			case 'lte':
				return 'goes <=';
				break;
			case 'change':
				return 'changes';
				break;
			case 'eq':
				return '==';
				break;	
			case 'frozen':
				return 'goes frozen';
				break;	
			case 'live':
				return 'goes live';
				break;																	
			default:
				return '';
				break;
		}
	}
}