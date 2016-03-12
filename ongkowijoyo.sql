-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2014 at 09:22 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ongkowijoyo`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang`
--

CREATE TABLE IF NOT EXISTS `tb_barang` (
  `id_barang` int(11) NOT NULL AUTO_INCREMENT,
  `kode_barang` char(32) NOT NULL,
  `nama_barang` varchar(70) NOT NULL,
  `nama_supplier` char(32) NOT NULL,
  `nama_satuan` char(32) NOT NULL,
  `stok_awal` bigint(20) NOT NULL,
  `tahun_produksi` int(4) NOT NULL,
  PRIMARY KEY (`id_barang`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tb_barang`
--

INSERT INTO `tb_barang` (`id_barang`, `kode_barang`, `nama_barang`, `nama_supplier`, `nama_satuan`, `stok_awal`, `tahun_produksi`) VALUES
(2, 'BRG001', 'Kertas Putih', 'CV. Terang Fajar', 'Rim', 20, 2014),
(3, 'BRG002', 'Kertas Klobot', 'PT. Adi Putra', 'Rim', 10, 2014),
(4, 'BRG003', 'Kertas KKI', 'CV. Terang Fajar', 'Rim', 10, 2014);

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang_jadi`
--

CREATE TABLE IF NOT EXISTS `tb_barang_jadi` (
  `id_barang_jadi` int(11) NOT NULL AUTO_INCREMENT,
  `kode_barang_jadi` char(32) NOT NULL,
  `nama_barang_jadi` varchar(70) NOT NULL,
  `nama_satuan` char(32) NOT NULL,
  `stok_awal` bigint(20) NOT NULL,
  PRIMARY KEY (`id_barang_jadi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_jurnal_barang_jadi`
--

CREATE TABLE IF NOT EXISTS `tb_jurnal_barang_jadi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `kode` char(32) NOT NULL,
  `nama` char(32) NOT NULL,
  `status` enum('Barang Masuk','Barang Keluar') NOT NULL,
  `tanggal` date NOT NULL,
  `kode_barang` char(32) NOT NULL,
  `nama_barang` char(32) NOT NULL,
  `jumlah` bigint(20) NOT NULL,
  `nama_satuan` char(20) NOT NULL,
  `isi` bigint(20) NOT NULL,
  `satuan_kecil` char(32) NOT NULL,
  `total` bigint(20) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `barang_ke` int(11) NOT NULL,
  PRIMARY KEY (`id_transaksi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_jurnal_transaksi`
--

CREATE TABLE IF NOT EXISTS `tb_jurnal_transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `kode` char(32) NOT NULL,
  `nama_supplier` char(32) NOT NULL,
  `status` enum('Barang Masuk','Barang Keluar') NOT NULL,
  `tanggal` date NOT NULL,
  `kode_barang` char(32) NOT NULL,
  `nama_barang` char(32) NOT NULL,
  `jumlah` bigint(20) NOT NULL,
  `nama_satuan` char(32) NOT NULL,
  `isi` bigint(20) NOT NULL,
  `nama_satuan_isi` char(32) NOT NULL,
  `total` bigint(20) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `barang_ke` int(32) NOT NULL,
  PRIMARY KEY (`id_transaksi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tb_jurnal_transaksi`
--

INSERT INTO `tb_jurnal_transaksi` (`id_transaksi`, `kode`, `nama_supplier`, `status`, `tanggal`, `kode_barang`, `nama_barang`, `jumlah`, `nama_satuan`, `isi`, `nama_satuan_isi`, `total`, `keterangan`, `barang_ke`) VALUES
(1, 'LPB01', 'CV. Terang Fajar', 'Barang Masuk', '2014-08-16', 'BRG001', 'Kertas Putih', 1, 'Rim', 500, 'Lembar', 500, '-', 1),
(2, 'LPB01', 'CV. Terang Fajar', 'Barang Masuk', '2014-08-16', 'BRG002', 'Kertas Klobot', 1, 'Rim', 500, 'Lembar', 500, '-', 2),
(3, 'PE001', 'CV. Bumi Ayu', 'Barang Keluar', '2014-08-18', 'BRG001', 'Kertas Putih', 2, 'Rim', 0, '', 1000, 'Pengeluaran Eksternal', 1),
(4, 'PE001', 'CV. Bumi Ayu', 'Barang Keluar', '2014-08-18', 'BRG002', 'Kertas Klobot', 1, 'Rim', 0, '', 500, 'Pengeluaran Eksternal', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pembelian`
--

CREATE TABLE IF NOT EXISTS `tb_pembelian` (
  `id_pembelian` int(11) NOT NULL AUTO_INCREMENT,
  `no_lpb` varchar(50) NOT NULL,
  `nama_supplier` varchar(50) NOT NULL,
  `admin` varchar(32) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id_pembelian`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tb_pembelian`
--

INSERT INTO `tb_pembelian` (`id_pembelian`, `no_lpb`, `nama_supplier`, `admin`, `tanggal`) VALUES
(1, 'LPB01', 'CV. Terang Fajar', 'gudang', '2014-08-16');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pembelian_detail`
--

CREATE TABLE IF NOT EXISTS `tb_pembelian_detail` (
  `no_lpb` char(32) NOT NULL,
  `kode_barang` char(32) NOT NULL,
  `nama_barang` varchar(70) NOT NULL,
  `jumlah` bigint(20) NOT NULL,
  `nama_satuan` char(32) NOT NULL,
  `isi` bigint(20) NOT NULL,
  `nama_satuan_isi` char(32) NOT NULL,
  `total` bigint(20) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `belanja_ke` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_pembelian_detail`
--

INSERT INTO `tb_pembelian_detail` (`no_lpb`, `kode_barang`, `nama_barang`, `jumlah`, `nama_satuan`, `isi`, `nama_satuan_isi`, `total`, `keterangan`, `belanja_ke`) VALUES
('LPB01', 'BRG001', 'Kertas Putih', 1, 'Rim', 500, 'Lembar', 500, '-', 1),
('LPB01', 'BRG002', 'Kertas Klobot', 1, 'Rim', 500, 'Lembar', 500, '-', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengeluaran`
--

CREATE TABLE IF NOT EXISTS `tb_pengeluaran` (
  `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT,
  `no_spi` char(32) NOT NULL,
  `kepada` char(32) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `admin` char(32) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id_pengeluaran`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tb_pengeluaran`
--

INSERT INTO `tb_pengeluaran` (`id_pengeluaran`, `no_spi`, `kepada`, `alamat`, `admin`, `tanggal`) VALUES
(1, 'PE001', 'CV. Bumi Ayu', 'Jl. Keramat Jati No. 11, Surabaya', 'gudang', '2014-08-18');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengeluaran_detail`
--

CREATE TABLE IF NOT EXISTS `tb_pengeluaran_detail` (
  `no_spi` char(32) NOT NULL,
  `kode_barang` char(32) NOT NULL,
  `nama_barang` varchar(50) NOT NULL,
  `jumlah` bigint(20) NOT NULL,
  `nama_satuan` char(32) NOT NULL,
  `satuan_kecil` char(20) NOT NULL,
  `total` bigint(20) NOT NULL,
  `pengeluaran_ke` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_pengeluaran_detail`
--

INSERT INTO `tb_pengeluaran_detail` (`no_spi`, `kode_barang`, `nama_barang`, `jumlah`, `nama_satuan`, `satuan_kecil`, `total`, `pengeluaran_ke`) VALUES
('PE001', 'BRG001', 'Kertas Putih', 2, 'Rim', 'Lembar', 1000, 1),
('PE001', 'BRG002', 'Kertas Klobot', 1, 'Rim', 'Lembar', 500, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengiriman_produksi`
--

CREATE TABLE IF NOT EXISTS `tb_pengiriman_produksi` (
  `id_pengiriman` int(11) NOT NULL AUTO_INCREMENT,
  `no_kirim` char(32) NOT NULL,
  `tanggal` date NOT NULL,
  `pengirim` char(32) NOT NULL,
  `admin` char(32) NOT NULL,
  `status_pengiriman` enum('Menunggu','Terkonfirmasi') NOT NULL,
  PRIMARY KEY (`id_pengiriman`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengiriman_produksi_detail`
--

CREATE TABLE IF NOT EXISTS `tb_pengiriman_produksi_detail` (
  `no_kirim` varchar(50) NOT NULL,
  `kode_barang_jadi` char(32) NOT NULL,
  `nama_barang_jadi` varchar(50) NOT NULL,
  `jumlah` bigint(20) NOT NULL,
  `nama_satuan` char(32) NOT NULL,
  `isi` bigint(20) NOT NULL,
  `total` bigint(20) NOT NULL,
  `satuan_kecil` char(32) NOT NULL,
  `pengiriman_ke` int(11) NOT NULL,
  `status_pengiriman` enum('Menunggu','Terkonfirmasi') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_permintaan_produksi`
--

CREATE TABLE IF NOT EXISTS `tb_permintaan_produksi` (
  `id_permintaan` int(11) NOT NULL AUTO_INCREMENT,
  `no_spi` char(100) NOT NULL,
  `tanggal` date NOT NULL,
  `admin` char(32) NOT NULL,
  `pengirim` varchar(30) NOT NULL,
  `status_permintaan` enum('Menunggu','Terkonfirmasi') NOT NULL,
  PRIMARY KEY (`id_permintaan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_permintaan_produksi_detail`
--

CREATE TABLE IF NOT EXISTS `tb_permintaan_produksi_detail` (
  `no_spi` char(100) NOT NULL,
  `kode_barang` char(10) NOT NULL,
  `nama_barang` varchar(50) NOT NULL,
  `jumlah_satuan` bigint(20) NOT NULL,
  `jenis_satuan` char(20) NOT NULL,
  `isi` bigint(20) NOT NULL,
  `satuan_kecil` char(20) NOT NULL,
  `total_satuan` int(20) NOT NULL,
  `permintaan_ke` int(11) NOT NULL,
  `status_permintaan` enum('Menunggu','Terkonfirmasi') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_satuan`
--

CREATE TABLE IF NOT EXISTS `tb_satuan` (
  `id_satuan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_satuan` char(32) NOT NULL,
  `nilai` bigint(20) NOT NULL,
  `satuan_kecil` char(20) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  PRIMARY KEY (`id_satuan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tb_satuan`
--

INSERT INTO `tb_satuan` (`id_satuan`, `nama_satuan`, `nilai`, `satuan_kecil`, `keterangan`) VALUES
(1, 'Rim', 500, 'Lembar', '1 Rim 500 Lembar');

-- --------------------------------------------------------

--
-- Table structure for table `tb_supplier`
--

CREATE TABLE IF NOT EXISTS `tb_supplier` (
  `id_supplier` int(11) NOT NULL AUTO_INCREMENT,
  `kode_supplier` char(32) NOT NULL,
  `nama_supplier` varchar(50) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tb_supplier`
--

INSERT INTO `tb_supplier` (`id_supplier`, `kode_supplier`, `nama_supplier`, `alamat`) VALUES
(1, ' S001', 'CV. Terang Fajar', 'Jl. Teluk Mandar No. 505, Malang'),
(2, ' S002', 'PT. Adi Putra', 'Jl. Mawar No. 10, Surabaya');

-- --------------------------------------------------------

--
-- Table structure for table `tb_tahun_produksi`
--

CREATE TABLE IF NOT EXISTS `tb_tahun_produksi` (
  `id_tahun_produksi` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_produksi` int(4) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  PRIMARY KEY (`id_tahun_produksi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tb_tahun_produksi`
--

INSERT INTO `tb_tahun_produksi` (`id_tahun_produksi`, `tahun_produksi`, `keterangan`) VALUES
(3, 2014, 'Buka tahun 2014');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE IF NOT EXISTS `tb_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(32) NOT NULL,
  `password` char(32) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `level` char(10) NOT NULL,
  `last_login` datetime NOT NULL,
  `ip` char(15) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `nama_lengkap`, `level`, `last_login`, `ip`) VALUES
(1, 'gudang', '202446dd1d6028084426867365b0c7a1', 'Pegawai Gudang', 'gudang', '2014-05-09 02:04:06', ''),
(2, 'produksi', 'edf3017a2946290b95c783bd1a7f0ba7', 'Pegawai Produksi', 'produksi', '2014-05-09 02:06:06', ''),
(3, 'sales', '9ed083b1436e5f40ef984b28255eef18', 'Pegawai Sales', 'sales', '0000-00-00 00:00:00', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
