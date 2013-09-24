<?php
	/*$sid = session_id();
	if(!empty($sid))
	{
		session_unset();
		session_destroy();
	}*/
	session_start();
	
	include("include/clsUtility.php");
	include("include/dbconfig.php");
	
	/*if(!isset($_SESSION['IDUSER']))
		$_SESSION['IDUSER']="";
	if(!isset($_SESSION['NAMALENGKAP']))
		$_SESSION['NAMALENGKAP']="";
	if(!isset($_SESSION['PENGGUNA']))
		$_SESSION['PENGGUNA']="";
	if(!isset($_SESSION['IDUSERLEVEL']))
		$_SESSION['IDUSERLEVEL']="";*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>SISTEM INFORMASI DATA MENARA TELEKOMUNIKASI</title>
		<link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/jquerycssmenu.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.searchFilter.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="themes/redmond/jquery-ui-1.8.2.custom.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.jqgrid.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.multiselect.css" />
		<script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/jquerycssmenu.js" type="text/javascript"></script>
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/jquery-ui.min.js" type="text/javascript"></script>
		<script src="js/jquery.layout.js" type="text/javascript"></script>
		<script src="js/i18n/grid.locale-en.js" type="text/javascript"></script>
		<script src="js/ui.multiselect.js" type="text/javascript"></script>
		<script type="text/javascript">
			$.jgrid.no_legacy_api = true;
			$.jgrid.useJSON = true;
		</script>
		<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
		<script src="js/jquery.tablednd.js" type="text/javascript"></script>
		<script src="js/jquery.contextmenu.js" type="text/javascript"></script>
		<script language='JavaScript'>
			var ajaxRequest;

			function getAjax() //fungsi untuk mengecek apakah browser mendukung AJAX
			{
				try
				{
					// Opera 8.0+, Firefox, Safari
					ajaxRequest = new XMLHttpRequest();
				}
				catch (e)
				{
					// Internet Explorer Browsers
					try
					{
						ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
					}
					catch (e) 
					{
						try
						{
							ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
						}
						catch (e)
						{
							// Something went wrong
							alert("Your browser broke!");
							return false;
						}
					}
				}
			}

			jQuery(document).ready(function(){
				jQuery('#userlvl').val("");
				jQuery('#adddata').val("false");
				jQuery('#editdata').val("false");
				jQuery('#deldata').val("false");
			});

			function getInfo(id)
			{
				jQuery('#page').load('map_info.php?idtower='+id);
				jQuery('#page').dialog({width:300,height:'auto',modal:true,title:'Informasi Tower/Menara'});
				jQuery('#list').after("<div id='page'></div>");
			}
			
			function getInfoBTS(type)
			{
				jQuery("#frmbts").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type='+type,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'idizin', 'No.', 'No. Izin Prinsip', 'Tgl. Surat Izin Prinsip', 'No. Izin HO', 'Tgl. Berlaku Izin HO', 'Tgl. Habis Izin HO', 'No. IMB', 'Tgl. Surat IMB', 'Nama File IMB', 'Lokasi Tower', 'Kecamatan', 'Nagari', 'Tinggi Tower (M)', 'Elevasi (M DPL)', 'Luas Tanah (M2)', 'RAB Tower (Rp)', 'Koordinat Bujur', 'Koordinat Lintang', 'Catudaya', 'Pemilik Tower', 'Alamat Kantor Pusat', 'Perwakilan', 'Alamat Perwakilan', 'Contact Person', 'No. Telepon', 'Pemilik Perusahaan', 'No. Tel. Pemilik', 'Operator', /*'Operator Yang Bergabung',*/ 'Pemilik Tanah', 'Status Tanah', 'Tgl. Akhir Kontrak'],
					colModel:[
						{name:'idtower',index:'idtower', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'idizin',index:'idizin', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'no_izin_prinsip',index:'i.no_izin_prinsip', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'No. Izin Prinsip'}}},
						{name:'tgl_izin_prinsip',index:'i.tgl_izin_prinsip', width:80, search:true, formatter:'date', formatoptions:{newformat:'d/m/Y'}, searchoptions:{sopt:['eq','le','ge'],dataInit:function(elem){jQuery(elem).datepicker({dateFormat: 'dd/mm/yy'});}}},
						{name:'no_izin_ho',index:'i.no_izin_ho', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'No. Izin HO'}}},
						{name:'tgl_izin_ho',index:'i.tgl_izin_ho', width:80, search:true, formatter:'date', formatoptions:{newformat:'d/m/Y'}, searchoptions:{sopt:['eq','le','ge'],dataInit:function(elem){jQuery(elem).datepicker({dateFormat: 'dd/mm/yy'});}}},
						{name:'tgl_hbs_izin_ho',index:'i.tgl_hbs_izin_ho', width:80, search:true, formatter:'date', formatoptions:{newformat:'d/m/Y'}, searchoptions:{sopt:['eq','le','ge'],dataInit:function(elem){jQuery(elem).datepicker({dateFormat: 'dd/mm/yy'});}}},
						{name:'no_imb',index:'i.no_imb', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'No. IMB'}}},
						{name:'tgl_imb',index:'i.tgl_imb', width:80, search:true, formatter:'date', formatoptions:{newformat:'d/m/Y'}, searchoptions:{sopt:['eq','le','ge'],dataInit:function(elem){jQuery(elem).datepicker({dateFormat: 'dd/mm/yy'});}}},
						{name:'file_imb',index:'i.file_imb', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Nama File IMB'}}},
						{name:'lokasi',index:'t.lokasi', width:150, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Lokasi'}}},	
						{name:'kecamatan',index:'t.idkec', width:100, search:true, stype:'select',searchoptions:{sopt:['eq'],value:getKecamatan(0)}},
						{name:'nagari',index:'n.nagari', width:120, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Nagari'}}},
						{name:'tinggi',index:'t.tinggi', width:60, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Tinggi'}}},	
						{name:'elevasi',index:'t.elevasi', width:80, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Elevasi'}}},	
						{name:'luas_tanah',index:'t.luas_tanah', width:60, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Luas Tanah'}}},	
						{name:'rab_tower',index:'t.rab_tower', width:80, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'RAB Tower'}}},	
						{name:'koord_b',index:'t.koord_b', width:100, search:false},	
						{name:'koord_l',index:'t.koord_l', width:100, search:false},
						{name:'catudaya',index:'c.catudaya', width:100, search:true, stype:'checkbox', searchoptions:{sopt:['eq'],attr:{title:'Catudaya'}}},				
						{name:'nama',index:'t.idpemilik', width:130, search:true, stype:'select', searchoptions:{sopt:['eq'],value:getPemilik(1),attr:{title:'Pemilik Tower'}}},
						{name:'alamat',index:'p.alamat', width:200, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Alamat Kantor Pusat'}}},		
						{name:'perwakilan',index:'p.perwakilan', width:150, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Perwakilan'}}},
						{name:'alamat_perwakilan',index:'p.alamat_perwakilan', width:200, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Alamat Perwakilan'}}},		
						{name:'contact_person',index:'p.contact_person', width:130, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Contact Person'}}},
						{name:'telp',index:'p.telp', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'No. Telp'}}},		
						{name:'pemilik',index:'p.pemilik', width:130, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Pemilik Perusahaan'}}},
						{name:'telp_pemilik',index:'p.telp_pemilik', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'No. Telp. Pemilik'}}},		
						{name:'noperator',index:'o.idoperator', width:100, search:true, stype:'select', searchoptions:{sopt:['eq'],value:getOperator(1),attr:{title:'Operator'}}},
						/*{name:'operator_gabung',index:'t.operator_gabung', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Operator Yang Bergabung'}}},*/
						{name:'pemilik_tanah',index:'t.pemilik_tanah', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Pemilik Tanah'}}},
						{name:'status_tanah',index:'t.status_tanah', width:80, search:true, stype:'select',searchoptions:{sopt:['eq'],value:["Milik Sendiri","Sewa"]}},
						{name:'akhir_kontrak',index:'t.akhir_kontrak', width:80, search:true, formatter:'date', formatoptions:{newformat:'d/m/Y'}, searchoptions:{sopt:['eq','le','ge'],dataInit:function(elem){jQuery(elem).datepicker({dateFormat: 'dd/mm/yy'});}}}
					],
					rowNum:25,
					rowList:[25,50,75,100],
					pager: '#page',
					sortname: 'idtower',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					/*editurl:'server.php?type='+type,
					autowidth:true,*/
					width: 970,
					height: 'auto',
					shrinkToFit:false,
					caption: "Data Menara Telekomunikasi Di Kabupaten Tanah Datar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery(".ui-jqgrid tr.jqgrow td").css('white-space', 'normal !important'); 
						jQuery(".ui-jqgrid tr.jqgrow td").css('height', 'auto');
						jQuery('#add_list').attr("title","Tambah Data Izin Menara");
						jQuery('#edit_list').attr("title","Edit/Perpanjang Data Izin Menara");
						jQuery('#del_list').attr("title","Hapus Data Izin Menara");
						jQuery('#search_list').attr("title","Cari Data Izin Menara");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data Izin Menara");
						jQuery(".ui-widget").css("font-size","0.7em");
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu",
				editfunc: function(id){ 
					jQuery('#frmbts').load('frmbts.php?id='+id);
					jQuery('#frmbts').dialog({width:670,height:550,modal:true,title:'Edit Data Menara Telekomunikasi',
					buttons: { "Batal": function() {
						jQuery(this).dialog("close"); 
						jQuery('#frmbts').remove();
						jQuery('#frm').prepend("<div id='frmbts'></div>");
					}, "Simpan": function() { 
						var idizin = jQuery("#idizin").val();
						var izin_prinsip = jQuery("#no_izin_prinsip").val();
						var izin_ho = jQuery("#no_izin_ho").val();
						var imb = jQuery("#no_imb").val();
						if(izin_prinsip == "" && izin_ho == "" && imb == "")
						{
							alert("Nomor Izin Belum Di Isi.");
							jQuery("#no_izin_prinsip").focus();
						}
						else
						{
							var validateIzin = jQuery.ajax({
								url: 'izin.php?id='+idizin+'&izin_prinsip='+izin_prinsip+'&izin_ho='+izin_ho+'&imb='+imb, 
								type:"POST",
								datatype: "html",
								async: false, 
								success: function(data, result) {
									if (!result) 
										alert('Data Izin Belum Ada.');
								}
							}).responseText;
							
							if(validateIzin == ""){
								dataString = jQuery("#frmsubmit").serialize();
								jQuery.ajax({
									url: 'frmbts.php', 
									type: "POST",
									data: dataString,
									datatype: "json"
								});
								jQuery(this).dialog("close"); 
								jQuery('#frmbts').remove();
								jQuery('#frm').prepend("<div id='frmbts'></div>");
								jQuery("#list").trigger("reloadGrid");
							}
							else
							{
								alert(validateIzin);
							}
						}
					} },
					});
				},
				addfunc: function(id){ 
					jQuery('#frmbts').load('frmbts.php');
					jQuery('#frmbts').dialog({width:670,height:550,modal:true,title:'Tambah Data Menara Telekomunikasi',
					buttons: { "Batal": function() {
						jQuery(this).dialog("close"); 
						jQuery('#frmbts').remove();
						jQuery('#frm').prepend("<div id='frmbts'></div>");
					}, "Simpan": function() {
						var izin_prinsip = jQuery("#no_izin_prinsip").val();
						var izin_ho = jQuery("#no_izin_ho").val();
						var imb = jQuery("#no_imb").val();
						if(izin_prinsip == "" && izin_ho == "" && imb == "")
						{
							alert("Nomor Izin Belum Di Isi.");
							jQuery("#no_izin_prinsip").focus();
						}
						else
						{
							var validateIzin = jQuery.ajax({
								url: 'izin.php?izin_prinsip='+izin_prinsip+'&izin_ho='+izin_ho+'&imb='+imb, 
								type:"POST",
								datatype: "html",
								async: false, 
								success: function(data, result) {
									if (!result) 
										alert('Data Izin Belum Ada.');
								}
							}).responseText;
							
							if(validateIzin == ""){
								dataString = jQuery("#frmsubmit").serialize();
								jQuery.ajax({
									url: 'frmbts.php', 
									type: "POST",
									data: dataString,
									datatype: "json"/*,
									success: function(data, result){
										alert(jQuery("#status_simpan").html());
									}*/
								});
								if(jQuery("#chkTutup").is(":checked")==false){
									jQuery(this).dialog("close"); 
									jQuery('#frmbts').remove();
									jQuery('#frm').prepend("<div id='frmbts'></div>");
								}
								else
								{
									//clear form
									jQuery("#status_simpan").html("<b>Data Berhasil Disimpan</b>");
									jQuery("#no_izin_prinsip").val("");
									jQuery("#tgl_izin_prinsip").val("");
									jQuery("#no_izin_ho").val("");
									jQuery("#tgl_izin_ho").val("");
									jQuery("#tgl_hbs_izin_ho").val("");
									jQuery("#no_imb").val("");
									jQuery("#tgl_imb").val("");
									jQuery("#lokasi").val("");
									jQuery("#tinggi").val("");
									jQuery("#elevasi").val("");
									jQuery('#koord_b').val("");
									if(jQuery("#selbujur").val()==0){
										jQuery('#koord_b_m').val("");
										jQuery('#koord_b_s').val("");
									}
									jQuery('#koord_l').val("");
									if(jQuery("#sellintang").val()==0){
										jQuery('#koord_l_m').val("");
										jQuery('#koord_l_s').val("");
									}
									jQuery("#pemilik_tanah").val("");
									jQuery('#tgl_akhir_kontrak').val("");
									jQuery("#no_izin_prinsip").focus();
								}
								jQuery("#list").trigger("reloadGrid");
							}
							else
							{
								alert(validateIzin);
							}
						}
					} },
					open: function(){
						jQuery(".ui-dialog-buttonpane").append("<div id='tutupform'><input type='checkbox' id='chkTutup'>Jangan Tutup Setelah Simpan</input></div>");	
						jQuery("#chkTutup").click(function(){
							if(jQuery("#chkTutup").is(":checked")==false){
								jQuery("#dontclose").val("0");
							}
							else
							{
								jQuery("#dontclose").val("1");
							}
						});						
					}
					});
				}				
				},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Menara Telekomunikasi",
					msg: "Apakah Anda Yakin Data Menara Telekomunikasi Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 400
				},
				//search the fourth
				{
					caption: "Cari Data Menara Telekomunikasi",
					closeOnEscape: true,
					closeAfterSearch: true,
					Find: "Cari",
					Reset: "Reset",
					multipleSearch:true,
					overlay:false
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Pilih Kolom Yang Ingin Di Tampilkan",
					buttonicon:"ui-icon-wrench", 
					onClickButton: function(){ 
						jQuery("#list").jqGrid("columnChooser",{
						caption:"Pilih Kolom Yang Ingin Di Tampilkan",
						bSubmit:"Tampilkan",
						bCancel:"Batal"});
						jQuery("#ui-dialog-title-colchooser_list").css("font-size","0.8em");
						jQuery(".ui-button-text").css("font-size","0.8em");
						//jQuery(".count:contains('items selected')").replace("items selected","Kolom Di Tampilkan");
						var txt = jQuery(".count").text();
						var obj = txt.split(" ");
						jQuery(".count").html(obj[0]+" Kolom Di Tampilkan");
						//jQuery(".remove-all").html("Hapus Semua Kolom");
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Izin Menara Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel(type);
					}
				});
				});
			}
			
			function getInfoBTSSelect(type)
			{
				if(type=="detail_operator")
				{
					jQuery("#frmbts").empty();
					jQuery("#frmbts").append("<table id='tblop'><tr><td><b>Pilih Operator :</b></td><td><select id='selop' onchange='getInfoBTSDetailOp();'></select></td></tr></table>");
					getOperator(0);
					getInfoBTSDetailOp();
				}
				else if(type=="detail_pemilik")
				{
					jQuery("#frmbts").empty();
					jQuery("#frmbts").append("<table id='tblpt'><tr><td><b>Pilih Pemilik Tower :</b></td><td><select id='selpt' onchange='getInfoBTSDetailPT();'></select></td></tr></table>");
					getPemilik(0);
					getInfoBTSDetailPT();
				}
				else if(type=="detail_kecamatan")
				{
					jQuery("#frmbts").empty();
					jQuery("#frmbts").append("<table id='tblop'><tr><td><b>Pilih Kecamatan :</b></td><td><select id='selkec' onchange='getInfoBTSDetailKec();'></select></td></tr></table>");
					getKecamatan(1);
					getInfoBTSDetailKec();
				}
				else if(type=="rekap_operator")
				{
					jQuery("#frmbts").empty();
					getInfoBTSRekapOp();
				}
				else if(type=="rekap_pemilik")
				{
					jQuery("#frmbts").empty();
					getInfoBTSRekapPT();
				}
				else if(type=="rekap_kecamatan")
				{
					jQuery("#frmbts").empty();
					getInfoBTSRekapKec();
				}
				else if(type=="expired")
				{
					jQuery("#frmbts").empty();
					jQuery("#frmbts").append("<table id='tblex'><tr><td><b>Pilih :</b></td><td><select id='selex' onchange='getInfoBTSDetailExp();'><option value='0'>Izin HO Telah Habis Masa Berlaku</option><option value='1'>Masa Berlaku Izin HO Habis Bulan Ini</option><option value='2'>Masa Berlaku Izin HO Habis Tahun Ini</option><option value='3'>Izin HO Masih Berlaku</option><!--option value='4'>Kontrak Tanah Sudah Habis</option--></select></td></tr></table>");
					getInfoBTSDetailExp();
				}
			}
			
			function getInfoBTSDetailOp()
			{
				var idop = jQuery("#selop").val();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=detail_operator&idoperator='+idop,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'No & Tgl Izin Prinsip/HO/IMB', 'Lokasi Menara/Tower', 'Tinggi & Elevasi<br>Tower', 'Koordinat', 'Pemilik Tower'],
					colModel:[
						{name:'idtower',index:'t.idtower', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'no_izin',index:'i.no_izin', width:200},
						{name:'lokasi',index:'t.lokasi', width:250},
						{name:'elevasi',index:'t.tinggi', width:70},
						{name:'koordinat',index:'t.koord_b', width:120},
						{name:'pemilik',index:'t.idpemilik', width:120, search:true, stype:'select', searchoptions:{sopt:['eq'],value:getPemilik(0),attr:{title:'Pemilik Tower'}}}
					],
					rowNum:25,
					rowList:[25,50,75,100],
					pager: '#page',
					sortname: 'idtower',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width:800,
					shrinkToFit:false,
					height:'auto',
					caption: "Data Menara Telekomunikasi Per Operator Di Kabupaten Tanah Datar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery('#search_list').attr("title","Cari Data");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data");
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:false,add:false,del:false,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{
					caption: "Cari Data Menara Telekomunikasi",
					closeOnEscape: true,
					closeAfterSearch: true,
					Find: "Cari",
					Reset: "Reset",
					multipleSearch:true,
					overlay:false
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel(type);
					}
				});
				});
			}
			
			function getInfoBTSDetailPT()
			{
				var idpt = jQuery("#selpt").val();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=detail_pemilik&idpemilik='+idpt,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'No & Tgl Izin Prinsip/HO/IMB', 'Lokasi Menara/Tower', 'Tinggi & Elevasi<br>Tower', 'Koordinat', 'Pemilik Tower'],
					colModel:[
						{name:'idtower',index:'t.idtower', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'no_izin',index:'i.no_izin', width:200},
						{name:'lokasi',index:'t.lokasi', width:250},
						{name:'elevasi',index:'t.tinggi', width:70},
						{name:'koordinat',index:'t.koord_b', width:120},
						{name:'pemilik',index:'t.idpemilik', width:120, search:true, stype:'select', searchoptions:{sopt:['eq'],value:getPemilik(1),attr:{title:'Pemilik Tower'}}}
					],
					rowNum:25,
					rowList:[25,50,75,100],
					pager: '#page',
					sortname: 'idtower',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width:800,
					shrinkToFit:false,
					height:'auto',
					caption: "Data Menara Telekomunikasi Per Pemilik Tower Di Kabupaten Tanah Datar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery('#search_list').attr("title","Cari Data");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data");
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:false,add:false,del:false,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{
					caption: "Cari Data Menara Telekomunikasi",
					closeOnEscape: true,
					closeAfterSearch: true,
					Find: "Cari",
					Reset: "Reset",
					multipleSearch:true,
					overlay:false
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel(type);
					}
				});
				});
			}
			
			function getInfoBTSDetailKec()
			{
				var idkec = jQuery("#selkec").val();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=detail_kecamatan&idkec='+idkec,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'No & Tgl Izin Prinsip/HO/IMB', 'Lokasi Menara/Tower', 'Tinggi & Elevasi<br>Tower', 'Koordinat', 'Pemilik Tower'],
					colModel:[
						{name:'idtower',index:'t.idtower', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'no_izin',index:'i.no_izin', width:200},
						{name:'lokasi',index:'t.lokasi', width:250},
						{name:'elevasi',index:'t.tinggi', width:70},
						{name:'koordinat',index:'t.koord_b', width:120},
						{name:'pemilik',index:'t.idpemilik', width:120}
					],
					rowNum:25,
					rowList:[25,50,75,100],
					pager: '#page',
					sortname: 'idtower',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width:800,
					shrinkToFit:false,
					height:'auto',
					caption: "Data Menara Telekomunikasi Per Kecamatan Di Kabupaten Tanah Datar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery('#search_list').attr("title","Cari Data");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data");
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:false,add:false,del:false,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel(type);
					}
				});
				});
			}
			
			function getInfoBTSRekapOp()
			{
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=rekap_operator',
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'Operator', 'Jumlah'],
					colModel:[
						{name:'idoperator',index:'idoperator', width:1, editable:false, hidden:true, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'nama',index:'nama', width:300},
						{name:'cnt',index:'cnt', width:100}
					],
					rowNum:20,
					rowList:[20,40],
					pager: '#page',
					sortname: 'idoperator',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width:500,
					shrinkToFit:false,
					height:'auto',
					caption: "Rekap Data Menara Telekomunikasi Per Operator Di Kabupaten Tanah Datar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery('#search_list').attr("title","Cari Data");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data");
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:false,add:false,del:false,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel(type);
					}
				});
				});
			}
			
			function getInfoBTSRekapPT()
			{
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=rekap_pemilik',
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'Pemilik Tower', 'Jumlah'],
					colModel:[
						{name:'idpemilik',index:'idpemilik', width:1, editable:false, hidden:true, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'nama',index:'nama', width:300},
						{name:'cnt',index:'cnt', width:100}
					],
					rowNum:20,
					rowList:[20,40],
					pager: '#page',
					sortname: 'idpemilik',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width:500,
					shrinkToFit:false,
					height:'auto',
					caption: "Rekap Data Menara Telekomunikasi Per Pemilik Tower Di Kabupaten Tanah Datar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery('#search_list').attr("title","Cari Data");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data");
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:false,add:false,del:false,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel(type);
					}
				});
				});
			}
			
			function getInfoBTSRekapKec()
			{
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=rekap_kecamatan',
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'Kecamatan', 'Jumlah'],
					colModel:[
						{name:'idkec',index:'idkec', width:1, editable:false, hidden:true, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'kecamatan',index:'kecamatan', width:300},
						{name:'cnt',index:'cnt', width:100}
					],
					rowNum:20,
					rowList:[20,40],
					pager: '#page',
					sortname: 'idkec',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width:500,
					shrinkToFit:false,
					height:'auto',
					caption: "Rekap Data Menara Telekomunikasi Per Kecamatan Di Kabupaten Tanah Datar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery('#search_list').attr("title","Cari Data");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data");
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:false,add:false,del:false,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel(type);
					}
				});
				});
			}
			
			function getInfoBTSDetailExp()
			{
				var idex = jQuery("#selex").val();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=expired&idex='+idex,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'No & Tgl Izin Prinsip/HO/IMB', 'Lokasi Menara/Tower', 'Tinggi & Elevasi<br>Tower', 'Koordinat', 'Pemilik Tower', 'Tanggal Habis Izin HO'],
					colModel:[
						{name:'idtower',index:'t.idtower', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'no_izin',index:'i.no_izin', width:200},
						{name:'lokasi',index:'t.lokasi', width:250},
						{name:'elevasi',index:'t.tinggi', width:70},
						{name:'koordinat',index:'t.koord_b', width:120},
						{name:'pemilik',index:'t.idpemilik', width:120},
						{name:'tgl_habis_izin_ho',index:'i.tgl_habis_izin_ho', width:120}
					],
					rowNum:25,
					rowList:[25,50,75,100],
					pager: '#page',
					sortname: 'idtower',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width:920,
					shrinkToFit:false,
					height:'auto',
					caption: "Data Menara Telekomunikasi Di Kabupaten Tanah Datar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery('#search_list').attr("title","Cari Data");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data");
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:false,add:false,del:false,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel(type);
					}
				});
				});
			}
			
			function getInfoMap(type)
			{
				jQuery("#frmbts").empty();
				jQuery("#list").jqGrid('GridUnload');
				getKecamatan(2);
				getOperator(2);
				jQuery("#chkOp").after("<div id='divUpdate'><input type='button' id='btnUpdate' value='Update Peta' onclick='loadMap(1)'/></div>");

				loadMap(0);
			}
			
			function loadMap(mode)
			{
				var idkec=0;
				var idop=0;
				var idpt=0;
				if(mode!=0)
				{
					var checked=0;
					jQuery("input[id*=kec]").each(function(i,val){
						if(jQuery(this).attr("checked"))
						{
							idkec+=jQuery(this).attr("value")+",";
							checked++;
						}
					});
					//alert(idkec);
					if(checked==0)
						idkec="";
					
					checked=0;
					jQuery("input[id*=checkOp]").each(function(i,val){
						if(jQuery(this).attr("checked"))
						{
							idop+=jQuery(this).attr("value")+",";
							checked++;
						}
					});
					if(checked==0)
						idop="";
				}
				var url = "loadmap.php?idkec="+idkec+"&idop="+idop;
				//var url = "loadmap.php";
				getAjax();
				ajaxRequest.open("GET",url);
				jQuery("#list").empty();
				document.getElementById("list").innerHTML = '<div style="text-align:center;"><img src="images/ajax-loader1.gif" width="100" height="100" /></div>';
				ajaxRequest.onreadystatechange = function()
				{
					document.getElementById("list").innerHTML = ajaxRequest.responseText;
				}
				ajaxRequest.send(null);
			}
			
			function getInfoOperator(type)
			{
				jQuery("#frmbts").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type='+type,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No', 'Operator', 'Kantor Pusat', 'Perwakilan', 'Alamat Perwakilan', 'Contact Person', 'No. Telp.', 'Pemilik Perusahaan', 'No. Telp. Pemilik'],
					colModel:[
						{name:'idoperator',index:'idoperator', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'nama',index:'nama', width:100, editable:true, editrules:{required:true}, editoptions: {size: 50, maxlength: 100}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Operator'}}},
						{name:'alamat',index:'alamat', width:200, editable:true, editrules:{required:true}, editoptions: {size: 60, maxlength: 250}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Kantor Pusat'}}},		
						{name:'perwakilan',index:'perwakilan', width:100, editable:true, editrules:{required:true}, editoptions: {size: 50, maxlength: 100}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Perwakilan'}}},
						{name:'alamat_perwakilan',index:'alamat_perwakilan', width:200, editable:true, editrules:{required:true}, editoptions: {size: 60, maxlength: 250}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Alamat Perwakilan'}}},		
						{name:'contact_person',index:'contact_person', width:100, editable:true, editrules:{required:true}, editoptions: {size: 50, maxlength: 100}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Contact Person'}}},
						{name:'telp',index:'telp', width:100, editable:true, editrules:{required:true}, editoptions: {size: 30, maxlength: 50}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'No. Telp'}}},		
						{name:'pemilik',index:'pemilik', width:100, editable:true, editrules:{required:true}, editoptions: {size: 100, maxlength: 100}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Pemilik Perusahaan'}}},
						{name:'telp_pemilik',index:'telp_pemilik', width:50, editable:true, editrules:{required:true}, editoptions: {size: 50, maxlength: 50}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'No. Telp. Pemilik'}}}		
					],
					rowNum:20,
					rowList:[20,40],
					pager: '#page',
					sortname: 'idoperator',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type='+type,
					caption: "Data Operator Menara Telekomunikasi Di Kabupaten Tanah Datar",
					loadComplete: function(){ 
						jQuery('#add_list').attr("title","Tambah Data Operator");
						jQuery('#edit_list').attr("title","Edit Data Operator");
						jQuery('#del_list').attr("title","Hapus Data Operator");
						jQuery('#search_list').attr("title","Cari Data Operator");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data Operator");
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery(".ui-jqgrid tr.jqgrow td").css('white-space', 'normal !important'); 
						jQuery(".ui-jqgrid tr.jqgrow td").css('height', 'auto');
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_nama', form).show();
                        jQuery('#tr_alamat', form).show();
                        jQuery('#tr_perwakilan', form).show();
                        jQuery('#tr_alamat_perwakilan', form).show();
                        jQuery('#tr_contact_person', form).show();
                        jQuery('#tr_telp', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Data Operator Telekomunikasi",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_nama', form).show();
                        jQuery('#tr_alamat', form).show();
                        jQuery('#tr_perwakilan', form).show();
                        jQuery('#tr_alamat_perwakilan', form).show();
                        jQuery('#tr_contact_person', form).show();
                        jQuery('#tr_telp', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("add",response);
                    },
					addCaption: "Tambah Data Operator Telekomunikasi",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterAdd:true,
					modal:true,
					resize:false,
					width: 400
                },
				//del the third
				{
					afterSubmit: function(response, postdata) {
						showResponse("del",response);
                    },
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Operator Telekomunikasi",
					msg: "Apakah Anda Yakin Data Operator Telekomunikasi Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 400
				},
				//search the fourth
				{
					caption: "Cari Data Operator Telekomunikasi",
					closeOnEscape: true,
					closeAfterSearch: true,
					Find: "Cari",
					Reset: "Reset",
					multipleSearch:true,
					overlay:false
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Operator Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel(type);
					}
				});
				});
			}	
			
			function getInfoPemilik(type)
			{
				jQuery("#frmbts").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type='+type,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No', 'Pemilik Tower', 'Kantor Pusat', 'Perwakilan', 'Alamat Perwakilan', 'Contact Person', 'No. Telp', 'Pemilik Perusahaan', 'No. Telp. Pemilik'],
					colModel:[
						{name:'idpemilik',index:'idpemilik', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'nama',index:'nama', width:100, editable:true, editrules:{required:true}, editoptions: {size: 50, maxlength: 100}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Pemilik Tower'}}},
						{name:'alamat',index:'alamat', width:200, editable:true, editrules:{required:true}, editoptions: {size: 60, maxlength: 250}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Kantor Pusat'}}},		
						{name:'perwakilan',index:'perwakilan', width:100, editable:true, editrules:{required:true}, editoptions: {size: 50, maxlength: 100}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Perwakilan'}}},
						{name:'alamat_perwakilan',index:'alamat_perwakilan', width:200, editable:true, editrules:{required:true}, editoptions: {size: 60, maxlength: 250}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Alamat Perwakilan'}}},		
						{name:'contact_person',index:'contact_person', width:100, editable:true, editrules:{required:true}, editoptions: {size: 50, maxlength: 100}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Contact Person'}}},
						{name:'telp',index:'telp', width:100, editable:true, editrules:{required:true}, editoptions: {size: 30, maxlength: 50}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'No. Telp'}}},		
						{name:'pemilik',index:'pemilik', width:100, editable:true, editrules:{required:true}, editoptions: {size: 100, maxlength: 100}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Pemilik Perusahaan'}}},
						{name:'telp_pemilik',index:'telp_pemilik', width:50, editable:true, editrules:{required:true}, editoptions: {size: 50, maxlength: 50}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'No. Telp. Pemilik'}}}		
					],
					rowNum:20,
					rowList:[20,40],
					pager: '#page',
					sortname: 'idpemilik',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type='+type,
					caption: "Data Pemilik Menara Telekomunikasi Di Kabupaten Tanah Datar",
					loadComplete: function(){ 
						jQuery('#add_list').attr("title","Tambah Data Pemilik Tower");
						jQuery('#edit_list').attr("title","Edit Data Pemilik Tower");
						jQuery('#del_list').attr("title","Hapus Data Pemilik Tower");
						jQuery('#search_list').attr("title","Cari Data Pemilik Tower");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data Pemilik Tower");
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery(".ui-jqgrid tr.jqgrow td").css('white-space', 'normal !important'); 
						jQuery(".ui-jqgrid tr.jqgrow td").css('height', 'auto');
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_nama', form).show();
                        jQuery('#tr_alamat', form).show();
                        jQuery('#tr_perwakilan', form).show();
                        jQuery('#tr_alamat_perwakilan', form).show();
                        jQuery('#tr_contact_person', form).show();
                        jQuery('#tr_telp', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Data Pemilik Menara Telekomunikasi",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_nama', form).show();
                        jQuery('#tr_alamat', form).show();
                        jQuery('#tr_perwakilan', form).show();
                        jQuery('#tr_alamat_perwakilan', form).show();
                        jQuery('#tr_contact_person', form).show();
                        jQuery('#tr_telp', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("add",response);
                    },
					addCaption: "Tambah Data Pemilik Menara Telekomunikasi",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterAdd:true,
					modal:true,
					resize:false,
					width: 400
                },
				//del the third
				{
					afterSubmit: function(response, postdata) {
						showResponse("del",response);
                    },
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Pemilik Menara Telekomunikasi",
					msg: "Apakah Anda Yakin Data Pemilik Menara Telekomunikasi Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 400
				},
				//search the fourth
				{
					caption: "Cari Data Pemilik Menara Telekomunikasi",
					closeOnEscape: true,
					closeAfterSearch: true,
					Find: "Cari",
					Reset: "Reset",
					multipleSearch:true,
					overlay:false
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Pemilik Tower Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel(type);
					}
				});
				});
			}
			
			function getInfoKecamatan(type)
			{
				jQuery("#frmbts").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type='+type,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No', 'Kecamatan'],
					colModel:[
						{name:'idkec',index:'idkec', width:0, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:50, search:false},
						{name:'kecamatan',index:'kecamatan', width:500, editable:true, editrules:{required:true}, editoptions: {size: 30, maxlength: 25}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Kecamatan'}}}	
					],
					rowNum:20,
					rowList:[20,40],
					pager: '#page',
					sortname: 'idkec',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type='+type,
					caption: "Data Kecamatan Di Kabupaten Tanah Datar",
					loadError : function(xhr,status,error) { 
						//jQuery("#rsperror").html("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
						alert(status+" "+error);
					},
					loadComplete: function(){ 
						jQuery('#add_list').attr("title","Tambah Data Kecamatan");
						jQuery('#edit_list').attr("title","Edit Data Kecamatan");
						jQuery('#del_list').attr("title","Hapus Data Kecamatan");
						jQuery('#search_list').attr("title","Cari Data Kecamatan");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data Kecamatan");
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_kecamatan', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Data Kecamatan",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_kecamatan', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("add",response);
                    },
					addCaption: "Tambah Data Kecamatan",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterAdd:true,
					modal:true,
					resize:false,
					width: 400
                },
				//del the third
				{
					afterSubmit: function(response, postdata) {
						showResponse("del",response);
                    },
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Kecamatan",
					msg: "Apakah Anda Yakin Data Kecamatan Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 350
				},
				//search the fourth
				{
					caption: "Cari Data Kecamatan",
					closeOnEscape: true,
					closeAfterSearch: true,
					Find: "Cari",
					Reset: "Reset",
					multipleSearch:false,
					overlay:false
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Kecamatan Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel(type);
					}
				});
				});
			}	
						
			function getInfoNagari(type)
			{
				jQuery("#frmbts").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type='+type,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No', 'Kecamatan', 'Nagari'],
					colModel:[
						{name:'idnagari',index:'b.idnagari', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:50, search:false},
						{name:'kecamatan',index:'a.idkec', width:250, editable:true, search:true, edittype:'select', editoptions: {size:50}, stype:'select',searchoptions:{/*dataInit:getKecamatan(),*/sopt:['eq'],value:getKecamatan(0)}},
						{name:'nagari',index:'b.nagari', width:300, editable:true, editrules:{required:true}, editoptions: {size: 30, maxlength: 50}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Nagari'}}}		
					],
					rowNum:25,
					rowList:[25,50,75,100],
					pager: '#page',
					sortname: 'idnagari',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type='+type,
					caption: "Data Nagari Di Kabupaten Tanah Datar",
					loadComplete: function(){ 
						jQuery('#add_list').attr("title","Tambah Data Nagari");
						jQuery('#edit_list').attr("title","Edit Data Nagari");
						jQuery('#del_list').attr("title","Hapus Data Nagari");
						jQuery('#search_list').attr("title","Cari Data Nagari");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data Nagari");
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_kecamatan', form).show();
                        jQuery('#tr_nagari', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					beforeInitData: function(form) {
						var kec = jQuery.ajax({
							type: "POST",
							url: 'kecamatan.php', 
							datatype: "json",
							async: false, 
							success: function(data, result) {
								if (!result) 
									alert('Data Kecamatan Belum Ada');
							}
						}).responseText;
						jQuery('#list').jqGrid('setColProp','kecamatan', { editoptions: { value: kec} });
					},
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Data Nagari",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_kecamatan', form).show();
                        jQuery('#tr_nagari', form).show();
                    },
					afterShowForm: function(form) {
                        /*jQuery('.CaptionTD').removeClass();*/
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					beforeInitData: function(form) {
						var kec = jQuery.ajax({
							type: "POST",
							url: 'kecamatan.php', 
							datatype: "json",
							async: false, 
							success: function(data, result) {
								if (!result) 
									alert('Data Kecamatan Belum Ada');
							}
						}).responseText;
						jQuery('#list').jqGrid('setColProp','kecamatan', { editoptions: { value: kec} });
					},
					afterSubmit: function(response, postdata) {
						showResponse("add",response);
                    },
					addCaption: "Tambah Data Nagari",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterAdd:true,
					modal:true,
					resize:false,
					width: 400
                },
				//del the third
				{
					afterSubmit: function(response, postdata) {
						showResponse("del",response);
                    },
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Nagari",
					msg: "Apakah Anda Yakin Data Nagari Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 350
				},
				//search the fourth
				{
					recreateForm: true,
					caption: "Cari Data Nagari",
					closeOnEscape: true,
					closeAfterSearch: true,
					Find: "Cari",
					Reset: "Reset",
					multipleSearch:true,
					overlay:false
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Nagari Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel(type);
					}
				});
				});
			}
			
			function getAdmin(mode)
			{ 
				if(jQuery("a:#login").html() == "Login")
				{
					jQuery(".ui-widget").css("font-size","0.8em");
					jQuery('#frmbts').load('login.php');
					jQuery('#frmbts').dialog({width:300,height:'auto',modal:true,title:'Login Pengguna',
					buttons: { "Batal": function() {
						jQuery(".ui-widget").css("font-size","0.7em");
						jQuery(this).dialog("close"); 
						jQuery("#frmbts").remove();
						jQuery("#frm").prepend("<div id='frmbts'></div>");
					}, "Login": function() { 
						var pengguna = jQuery("#nama_pengguna").val();
						var pass = jQuery("#pass_pengguna").val();
						if(pengguna == "" || pass == "")
						{
							alert("Nama Pengguna dan Password Belum Di Isi.");
							jQuery("#nama_pengguna").focus();
						}
						else
						{
							var validateLogin = jQuery.ajax({
								url: 'login.php?pengguna='+pengguna+'&pass='+pass, 
								type:"POST",
								datatype: "json",
								async: false, 
								success: function(data, result) {
									if (!result) 
										alert('Data Pengguna Belum Ada.');
								}
							}).responseText;
							//alert(validateLogin);
							var res = validateLogin.split(";");
							var result = res[0].split(":");
					
							if(result[0] == "result" && result[1] == "OK"){
								var nama = res[1].split(":");
								var userlvl = res[2].split(":");
								var adddata = res[3].split(":");
								var editdata = res[4].split(":");
								var deldata = res[5].split(":");
								if(userlvl[1]==1)	//level administrator
								{
									jQuery('li#setting_admin').attr('style','');
								}
								jQuery('#userlvl').val(userlvl[1]);
								jQuery('#adddata').val(adddata[1]);
								jQuery('#editdata').val(editdata[1]);
								jQuery('#deldata').val(deldata[1]);
								jQuery("a:#login").html("Logout");
								jQuery("li#loginname").append("<a href='#' style='color:yellow'>"+nama[1]+"</a>");
								jQuery(".ui-widget").css("font-size","0.7em");
								jQuery(this).dialog("close"); 
								//clear page
								jQuery("#frmbts").remove();
								jQuery("#frm").prepend("<div id='frmbts'></div>");
								jQuery("#list").jqGrid('GridUnload');
								jQuery("#list").empty();
							}
							else
							{
								alert(validateLogin);
							}
						}
					} },
					});
				}
				else	//Logout
				{
					jQuery.ajax({
						url: 'login.php?logout=true', 
						type:"POST",
						datatype: "json",
						async: false, 
						success: function(data, result) {
							if (!result) 
								alert('Data Pengguna Belum Ada.');
						}
					});
					jQuery('#userlvl').val("");
					jQuery('#adddata').val("false");
					jQuery('#editdata').val("false");
					jQuery('#deldata').val("false");
					jQuery("a:#login").html("Login");
					jQuery("li#setting_admin").attr("style","display:none");
					jQuery("li#loginname").html("");
					//clear page
					jQuery("#frmbts").empty();
					jQuery("#list").jqGrid('GridUnload');
					jQuery("#list").empty();
				}
			}
				
			function getInfoUser(type)
			{
				jQuery("#frmbts").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type='+type,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No', 'Ubah Password', 'Nama Lengkap', 'Pengguna', 'Password Lama', 'Password Baru', 'Tingkatan', 'Status'],
					colModel:[
						{name:'iduser',index:'iduser', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:50, search:false},
						{name:'changepass',index:'changepass', width:1, editable:true, edittype:'checkbox', hidden:true, editrules:{edithidden:true}, search:false},
						{name:'nama_lengkap',index:'nama_lengkap', width:150, editable:true, editrules:{required:true}, editoptions: {size: 30, maxlength: 50}},
						{name:'user',index:'user', width:100, editable:true, editrules:{required:true}, editoptions: {size: 30, maxlength: 50}},
						{name:'password',index:'password', width:1, editable:true, edittype:'password', hidden:true, editrules:{edithidden:true}, search:false},
						{name:'password2',index:'password2', width:1, editable:true, edittype:'password', hidden:true, editrules:{edithidden:true}, search:false},
						{name:'userlevel',index:'userlevel', width:100, editable:true, edittype:'select', editoptions: {size:50}},
						{name:'status',index:'status', width:100, editable:true, edittype:'select', editoptions:{value:getStatus(0)}}		
					],
					rowNum:10,
					rowList:[10,20],
					pager: '#page',
					sortname: 'iduser',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type='+type,
					caption: "Data Pengguna",
					loadComplete: function(){ 
						jQuery('#add_list').attr("title","Tambah Data Pengguna");
						jQuery('#edit_list').attr("title","Edit Data Pengguna");
						jQuery('#del_list').attr("title","Hapus Data Pengguna");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data Pengguna");
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
						
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:false,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					beforeShowForm: function(form) {
						jQuery('#changepass').change(function(){changePass();});
                        jQuery('#password').attr("disabled","disabled");
                        jQuery('#password2').attr("disabled","disabled");
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","35%");
						jQuery('.FormElement').css("width","90%");
                    },
					beforeInitData: function(form) {
						var userlevel = jQuery.ajax({
							type: "POST",
							url: 'userlevel.php', 
							datatype: "json",
							async: false, 
							success: function(data, result) {
								if (!result) 
									alert('Data Tingkatan Belum Ada');
							}
						}).responseText;
						jQuery('#list').jqGrid('setColProp','userlevel', { editoptions: { value: userlevel} });
					},
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Data Pengguna",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{
					beforeShowForm: function(form) {
						jQuery('#tr_changepass').hide();
						jQuery('#tr_password td.CaptionTD').html("Password");
						jQuery('#tr_password2 td.CaptionTD').html("Password (Lagi)");
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","35%");
						jQuery('.FormElement').css("width","90%");
                    },
					beforeInitData: function(form) {
						var userlevel = jQuery.ajax({
							type: "POST",
							url: 'userlevel.php', 
							datatype: "json",
							async: false, 
							success: function(data, result) {
								if (!result) 
									alert('Data Tingkatan Belum Ada');
							}
						}).responseText;
						jQuery('#list').jqGrid('setColProp','userlevel', { editoptions: { value: userlevel} });
					},
					afterSubmit: function(response, postdata) {
						showResponse("add",response);
                    },
					addCaption: "Tambah Data Pengguna",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterAdd:true,
					modal:true,
					resize:false,
					width: 400
                },
				//del the third
				{
					afterSubmit: function(response, postdata) {
						showResponse("del",response);
                    },
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Pengguna",
					msg: "Apakah Anda Yakin Data Pengguna Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 350
				},
				//search the fourth
				{});
				});
			}
			
			function getReport(type)
			{
				if(type=="retribusi")
				{
					jQuery(".ui-widget").css("font-size","0.8em");
					jQuery('#frmbts').load('choose_pemilik.php');
					jQuery('#frmbts').dialog({width:300,height:'auto',modal:true,title:'Pilih Pemilik Tower',
					buttons: { "Batal": function() {
						jQuery(".ui-widget").css("font-size","0.7em");
						jQuery(this).dialog("close"); 
						jQuery("#frmbts").remove();
						jQuery("#frm").prepend("<div id='frmbts'></div>");
					}, "Pilih": function() { 
						var idpemilik = jQuery("#c_pemilik").val();
						window.open('rep_retribusi_excel.php?idpemilik='+idpemilik);
					} },
					});
					//window.open('rep_retribusi_excel.php');
				} else if (type=="data_tower")
				{
					window.open('rep_datatower_excel.php');
					//window.open('rep_data_tower.php');
				}
			}
			
			function getInfoUserLevel(type)
			{
				jQuery("#frmbts").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type='+type,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No', 'Tingkatan'],
					colModel:[
						{name:'iduserlevel',index:'iduserlevel', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:50, search:false},
						{name:'userlevel',index:'userlevel', width:400, editable:true, editrules:{required:true}, editoptions: {size: 30, maxlength: 50}}	
					],
					rowNum:10,
					rowList:[10,20],
					pager: '#page',
					sortname: 'iduserlevel',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type='+type,
					caption: "Data Tingkatan",
					loadComplete: function(){ 
						jQuery('#add_list').attr("title","Tambah Data Tingkatan");
						jQuery('#edit_list').attr("title","Edit Data Tingkatan");
						jQuery('#del_list').attr("title","Hapus Data Tingkatan");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data Tingkatan");
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:false,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Data Tingkatan",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("add",response);
                    },
					addCaption: "Tambah Data Tingkatan",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterAdd:true,
					modal:true,
					resize:false,
					width: 400
                },
				//del the third
				{
					afterSubmit: function(response, postdata) {
						showResponse("del",response);
                    },
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Tingkatan",
					msg: "Apakah Anda Yakin Data Tingkatan Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 350
				},
				//search the fourth
				{});
				});
			}
			
			function getInfoUserAccess(type)
			{
				jQuery("#frmbts").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type='+type,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'IDUserLevel', 'No', 'Tingkatan', 'Tambah Data', 'Edit Data', 'Hapus Data'],
					colModel:[
						{name:'iduseraccess',index:'iduseraccess', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'iduserlevel',index:'iduserlevel', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:50, search:false},
						{name:'userlevel',index:'userlevel', width:250, editable:true, editrules:{required:false}, editoptions: {size: 30, maxlength: 50}},	
						{name:'adddata',index:'adddata', width:100, editable:true, edittype:'select', editoptions:{value:getStatus(1)}},		
						{name:'editdata',index:'editdata', width:100, editable:true, edittype:'select', editoptions:{value:getStatus(1)}},		
						{name:'deldata',index:'deldata', width:100, editable:true, edittype:'select', editoptions:{value:getStatus(1)}}		
					],
					rowNum:10,
					rowList:[10,20],
					pager: '#page',
					sortname: 'iduseraccess',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type='+type,
					caption: "Data Tingkatan",
					loadComplete: function(){ 
						jQuery('#edit_list').attr("title","Edit Data Akses Pengguna");
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:false,del:false,search:false,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					beforeShowForm: function(form) {
                        jQuery('#userlevel').attr("disabled","disabled");
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Data Akses Pengguna",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{});
				});
			}
			
			function exportExcel(typeinfo)
			{
				var mya=new Array();
				mya=jQuery("#list").jqGrid('getDataIDs');  // Get All IDs
				var data_h=jQuery("#list").jqGrid('getRowData',mya[0]);     // Get First row to get the labels
				var colNames=new Array(); 
				var ii=0;
				for (var i in data_h){
					if(jQuery("#list_"+i+"").css("display")!="none")
					{
						colNames[ii++]=i;
					}
				}    // capture col names
				var html="";
				for(k=0;k<colNames.length;k++)
				{
					html=html+colNames[k]+"\t"; // output each column as tab delimited
				}
				html=html+"\n";  // output each row with end of line
				var rplc = new RegExp("\n");
				for(i=0;i<mya.length;i++)
				{
					data=jQuery("#list").jqGrid('getRowData',mya[i]); // get each row
					for(j=0;j<colNames.length;j++)
					{
						//var val = data[colNames[j]].toString();
						var val = data[colNames[j]].toString();
						if(val.search(rplc) != -1)
						{
							//val = val.replace(rplc," ");
							var valsplit = val.split(rplc);
							if(valsplit.length>0)
							{
								var valjoin = "";
								for(l=0;l<valsplit.length;l++)
								{
									valjoin += valsplit[l]+" ";
								}
								val = valjoin;
							}
						}
						html=html+" "+val.valueOf()+"\t"; // output each column as tab delimited
					}
					html=html+"\n";  // output each row with end of line

				}
				html=html+"\n";  // end of line at the end
				document.forms[0].csvBuffer.value=html;
				document.forms[0].typeinfo.value=typeinfo;
				document.forms[0].method='POST';
				document.forms[0].action='csvExport.php';  // send it to server which will open this contents in excel file
				document.forms[0].target='_blank';
				document.forms[0].submit();
			}
			
			function getStatus(mode)
			{
				var status = "";
				if(mode==0)
					status = "1:Aktif;0:Non Aktif";
				else
					status = "1:Bisa;0:Tidak Bisa";
				
				return status;
			}		
			
			function getKecamatan(mode)
			{
				var kec = jQuery.ajax({
					url: 'kecamatan.php?search=true', 
					type:"POST",
					datatype: "json",
					async: false, 
					success: function(data, result) {
						if (!result) 
							alert('Data Kecamatan Belum Ada.');
					}
				}).responseText;
				
				if(mode==0)
				{
					return kec;
				}
				else if(mode==1)
				{
					var kecamatan = kec.split(";");
					jQuery.each(kecamatan,function(i,val){
						var obj = val.split(":");
						jQuery("#selkec").append("<option value='"+obj[0]+"'>"+obj[1]+"</option>");
					});
				}
				else if(mode==2)
				{
					jQuery("#frmbts").append("<div id='chkKec' style='background-color:yellow;font-size:0.7em;'></div><input type='hidden' id='jmlKec' value=''>");
					var j=0;
					var kecamatan = kec.split(";");
					jQuery.each(kecamatan,function(i,val){
						var obj = val.split(":");
						if(obj[0]==0)
						{
							jQuery("#chkKec").append("<input id='selAllK' name='selAllK' type='checkbox' checked='checked' value='0'>"+obj[1]+"</input>&nbsp;&nbsp;");
							jQuery("#selAllK").click(function(){checkAll(0);});
						}
						else
						{
							jQuery("#chkKec").append("<input id='kec"+obj[0]+"' name='kec"+obj[0]+"' type='checkbox' checked='checked' value='"+obj[0]+"'>"+obj[1]+"</input>&nbsp;&nbsp;");
						}
						j++;
					});
					jQuery("#jmlKec").val(j);
				}
			}		
			
			function getPemilik(mode)
			{
				var pemilik = jQuery.ajax({
					url: 'pemilik.php?search=true', 
					type:"POST",
					datatype: "json",
					async: false, 
					success: function(data, result) {
						if (!result) 
							alert('Data Pemilik Tower Belum Ada.');
					}
				}).responseText;
				
				if(mode==0)
				{
					var pt = pemilik.split(";");
					jQuery.each(pt,function(i,val){
						var obj = val.split(":");
						jQuery("#selpt").append("<option value='"+obj[0]+"'>"+obj[1]+"</option>");
					});
				}
				else if(mode==1)
				{
					return pemilik;
				}
				else if(mode==2)
				{
					jQuery("#chkKec").after("<div id='chkPt' style='background-color:pink;font-size:0.7em;'></div>");
					var pt = pemilik.split(";");
					jQuery.each(pt,function(i,val){
						var obj = val.split(":");
						if(obj[0]==0)
						{
							jQuery("#chkPt").append("<input id='sellAllPt' name='sellAllPt' type='checkbox' checked='checked' value='0'>"+obj[1]+"</input>&nbsp;&nbsp;");
							jQuery("#sellAllPt").click(function(){checkAll(1);});
						}
						else
						{
							jQuery("#chkPt").append("<input id='checkPt"+obj[0]+"' name='checkPt"+obj[0]+"' type='checkbox' checked='checked' value='"+obj[0]+"'>"+obj[1]+"</input>&nbsp;&nbsp;");
						}
					});
				}
			}
			
			function getOperator(mode)
			{
				var op = jQuery.ajax({
					url: 'operator.php?search=true&sel=false', 
					type:"POST",
					datatype: "json",
					async: false, 
					success: function(data, result) {
						if (!result) 
							alert('Data Operator Belum Ada.');
					}
				}).responseText;
				
				if(mode==0)
				{
					var operator = op.split(";");
					jQuery.each(operator,function(i,val){
						var obj = val.split(":");
						jQuery("#selop").append("<option value='"+obj[0]+"'>"+obj[1]+"</option>");
					});
				}
				else if(mode==1)
				{
					return op;
				}
				else if(mode==2)
				{
					jQuery("#chkKec").after("<div id='chkOp' style='background-color:pink;font-size:0.7em;'></div>");
					var operator = op.split(";");
					jQuery.each(operator,function(i,val){
						var obj = val.split(":");
						if(obj[0]==0)
						{
							jQuery("#chkOp").append("<input id='sellAllOp' name='sellAllOp' type='checkbox' checked='checked' value='0'>"+obj[1]+"</input>&nbsp;&nbsp;");
							jQuery("#sellAllOp").click(function(){checkAll(1);});
						}
						else
						{
							jQuery("#chkOp").append("<input id='checkOp"+obj[0]+"' name='checkOp"+obj[0]+"' type='checkbox' checked='checked' value='"+obj[0]+"'>"+obj[1]+"</input>&nbsp;&nbsp;");
						}
					});
				}
			}		
			
			function changePass()
			{
				if(jQuery("#changepass").is(":checked"))
				{
					jQuery("#password").removeAttr("disabled");
					jQuery("#password2").removeAttr("disabled");
				}
				else
				{
					jQuery("#password").attr("disabled","disabled");
					jQuery("#password2").attr("disabled","disabled");
				}
			}
			
			function showResponse(mode,response)
			{
				if(response.responseText != "")
				{
					alert(response.responseText);
				}
				jQuery("#cData").click();
				if(mode=="del")
					jQuery("#eData").click();
				jQuery("#list").trigger("reloadGrid");
			}
			
			function checkAll(mode)
			{
				if(mode==0)
				{
					if(jQuery("#selAllK").is(":checked"))
					{
						jQuery("input[id*=kec]").attr("checked","checked");
					}
					else
					{
						jQuery("input[id*=kec]").attr("checked","");
					}
				}
				else if(mode==1)
				{
					if(jQuery("#sellAllOp").is(":checked"))
					{
						jQuery("input[id*=checkOp]").attr("checked","checked");
					}
					else
					{
						jQuery("input[id*=checkOp]").attr("checked","");
					}
				}
				else if(mode==2)
				{
					if(jQuery("#sellAllPt").is(":checked"))
					{
						jQuery("input[id*=checkPt]").attr("checked","checked");
					}
					else
					{
						jQuery("input[id*=checkPt]").attr("checked","");
					}
				}
			}
			
		</script>
		<style type="text/css">
			/* set height grid header -- jgn pake, chrome menjadi berat sekali*/
			.ui-jqgrid-htable th {
				height:auto;
				overflow:hidden;
				padding-right:4px;
				padding-top:2px;
				position:relative;
				vertical-align:text-middle;
				white-space:normal !important;
			}
		</style>
	</head>
	<body>
		<div id="header"></div>
		<div id="menu" class="jquerycssmenu"><?php include("menu.php");?></div>
		<div id="content">
			<form id="frm" method="post" action="csvExport.php">
				<div id="frmbts"></div>
				<!--span id="rsperror" style="color:red"></span>
				<br/-->
				<table id="list"></table>
				<div id="page"></div>
				<input id="csvBuffer" name="csvBuffer" type="hidden" value="">
				<input id="typeinfo" name="typeinfo" type="hidden" value="">
			</form>
			<input id="userlvl" type="hidden" value="">
			<input id="adddata" type="hidden" value="false">
			<input id="editdata" type="hidden" value="false">
			<input id="deldata" type="hidden" value="false">
		</div>
		<div id="footer">
			Copyright @2011 Dinas Perhubungan Komunikasi & Informasi<br>
			Kabupaten Tanah Datar<br>
			Terminal Piliang, Dobok, Batusangkar
		</div>
	</body>
</html>