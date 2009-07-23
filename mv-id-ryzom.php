<?php
/*
Plugin Name: MV-ID: Ryzom
Plugin URI: http://signpostmarv.name/mv-id/
Description: Display your identity from Ryzom!
Version: 0.1
Author: SignpostMarv Martin
Author URI: http://signpostmarv.name/
For the purposes of the Ryzom Summer Coding Contest, this plugin is available under the GNU AGPLv3 http://www.fsf.org/licensing/licenses/agpl-3.0.html
 Copyright 2009 SignpostMarv Martin  (email : ryzom.mv-id.wp@signpostmarv.name)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class mv_id_vcard_ryzom extends mv_id_vcard
{
	const sprintf_img         = '#';
	const sprintf_url         = '#';
	public static function id_format()
	{
		return '\'Partial Character Key\'';
	}
	public static function is_id_valid($id)
	{
		return (bool)preg_match('/^([\w\d]+)$/',$id);
	}
	public static function affiliations_label()
	{
		return 'Guild';
	}
	public static function register_metaverse()
	{
		mv_id_plugin::register_metaverse('Ryzom','Ryzom','mv_id_vcard_ryzom');
	}
	public static function get_widget(array $args)
	{
		self::get_widgets('Ryzom',$args);
	}
	public static function factory($id,$last_mod=false)
	{
		if(self::is_id_valid($id) === false)
		{
			return false;
		}
		else
		{
			$url = sprintf('http://atys.ryzom.com/api/character.php?key=%1$s&part=partial',$id);
			$curl_opts = array();
			if($last_mod !== false)
			{
				$curl_opts['headers'] = array(
					'If-Modified-Since'=>$last_mod,
				);
			}
			$data = mv_id_plugin::curl(
				$url,
				$curl_opts
			);
			if($data === true)
			{
				return true;
			}
			$temp = tempnam(sys_get_temp_dir(),'ryzom');
			file_put_contents($temp,$data);
			$data = implode("\n",gzfile($temp));
			unlink($temp);
			if((($XML = mv_id_plugin::SimpleXML($data)) instanceof SimpleXMLElement) === false)
			{
				return false;
			}
			else
			{
				$data = array();
				$data['name'] = mv_id_plugin::XPath($XML,'./name');
				$data['shard'] = mv_id_plugin::XPath($XML,'./shard');
				$data['race'] = mv_id_plugin::XPath($XML,'./race');
				$data['gender'] = mv_id_plugin::XPath($XML,'./gender');
				$data['title'] = mv_id_plugin::XPath($XML,'./titleid');
				$data['logout'] = mv_id_plugin::XPath($XML,'./latest_logout');
				$gender = array('f'=>'female','m'=>'male');
				if(in_array(false,$data) === true)
				{
					return false;
				}
				else
				{
					foreach($data as $k=>$v)
					{
						$data[$k] = (string)current($v);
					}
				}
				$data['gender'] = $gender[$data['gender']];
				$description = sprintf(__('%5$s %1$s is a %4$s %3$s, who was last seen in the %2$s shard on %6$s.'),$data['name'],$data['shard'],$data['race'],$data['gender'],$data['title'],date('l jS, F Y',$data['logout']));
				$data['guild'] = mv_id_plugin::XPath($XML,'./guild');
				if($data['guild'] === false)
				{
					$data['guild'] = null;
				}
				else
				{
					$data['guild'] = array(new mv_id_vcard_affiliation((string)current(mv_id_plugin::XPath($XML,'./guild/name')),false,sprintf('http://atys.ryzom.com/api/guild_icon.php?icon=%1$s&amp;size=s',(string)current(mv_id_plugin::XPath($XML,'./guild/gid')))));
				}
				return new self($id,$data['name'],null,$description,null,null,$data['guild']);
			}
		}
	}
}
add_action('mv_id_plugin__register_metaverses','mv_id_vcard_ryzom::register_metaverse');
?>