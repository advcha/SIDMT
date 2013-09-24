<ul>
    <li>
		<a id="bts" href="#" onclick="getInfoBTS('bts_all');">Data Menara Telekomunikasi</a>
		<ul style="z-index:2;">
			<li><a href="#" onclick="getInfoBTSSelect('detail_operator');">Data Per Operator</a></li>
			<li><a href="#" onclick="getInfoBTSSelect('detail_pemilik');">Data Per Pemilik Tower</a></li>
			<li><a href="#" onclick="getInfoBTSSelect('detail_kecamatan');">Data Per Kecamatan</a></li>
			<li><a href="#" onclick="getInfoBTSSelect('rekap_operator');">Data Rekap Per Operator</a></li>
			<li><a href="#" onclick="getInfoBTSSelect('rekap_pemilik');">Data Rekap Per Pemilik Tower</a></li>
			<li><a href="#" onclick="getInfoBTSSelect('rekap_kecamatan');">Data Rekap Per Kecamatan</a></li>
			<li><a href="#" onclick="getInfoBTSSelect('expired');">Data Izin Habis Berlaku</a></li>
		</ul>
	</li>
    <li><a href="#" id="map" onclick="getInfoMap('');">Peta Lokasi Menara Telekomunikasi</a></li>
    <li><a id="operator" href="#" onclick="getInfoOperator('operator');">Data Operator</a></li>
    <li><a id="pemilik_perusahaan" href="#" onclick="getInfoPemilik('pemilik_tower');">Data Pemilik Tower</a></li>
    <!--li><a id="kecamatan" href="#" onclick="getInfo('kecamatan.php');">Data Kecamatan & Nagari</a></li-->
    <li><a id="kecamatan" href="#" onclick="getInfoKecamatan('kecamatan');">Data Kecamatan</a></li>
    <li><a id="nagari" href="#" onclick="getInfoNagari('nagari');">Data Nagari</a></li>
    <li>
		<a href="#">Laporan</a>
		<ul style="z-index:2;">
			<li><a href="#" onclick="getReport('retribusi');">Daftar Tagihan Retribusi</a></li>
			<li><a href="#" onclick="getReport('data_tower');">Data Tower</a></li>
		</ul>
	</li>
    <!--li><a id="kecamatan" href="server.php?type=kecamatan">Data Kecamatan & Nagari</a></li-->
    <!--li><a href="#">Pencarian Data</a></li-->
	<li id="setting_admin" style="display:none">
		<a href='#'>Setting</a>
		<ul style='z-index:2;'>
			<li><a href='#' onclick="getInfoUser('user');">Data Pengguna</a></li>
			<li><a href='#' onclick="getInfoUserLevel('userlevel');">Data Tingkat Pengguna</a></li>
			<li><a href='#' onclick="getInfoUserAccess('useraccess');">Data Akses Pengguna</a></li>
		</ul>
	</li>
    <li><a id="login" href="#" onclick="getAdmin('login');">Login</a></li>
    <li id="loginname"></li>
</ul>