-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 11. Mai 2021 um 15:29
-- Server-Version: 10.1.47-MariaDB-0ubuntu0.18.04.1
-- PHP-Version: 7.0.33-0ubuntu0.16.04.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `heating`
--
CREATE DATABASE IF NOT EXISTS `heating` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `heating`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `deltasole`
--

CREATE TABLE `deltasole` (
  `index` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time` time NOT NULL,
  `solarpump` int(11) NOT NULL,
  `relay2` int(11) NOT NULL,
  `relay3` int(11) NOT NULL,
  `buffer_relay` int(11) NOT NULL,
  `ww_relay` int(11) NOT NULL,
  `rl_relay` int(11) NOT NULL,
  `heatingpump` int(11) NOT NULL,
  `temp_collector` double NOT NULL,
  `temp_ww_bottom` double NOT NULL,
  `temp_ww_top` double NOT NULL,
  `temp_buffer_bottom` double NOT NULL,
  `temp_buffer_mid` double NOT NULL,
  `temp_collector2` double NOT NULL,
  `temp_buffer_top` double NOT NULL,
  `temp_rl` double NOT NULL,
  `temp_wmz_vl` double NOT NULL,
  `temp_wmz_rl` double NOT NULL,
  `solarization` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `deltasole_wmz`
--

CREATE TABLE `deltasole_wmz` (
  `index` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time` time NOT NULL,
  `temp_vl` double NOT NULL,
  `temp_rl` double NOT NULL,
  `circulatory` int(11) NOT NULL,
  `energy` bigint(20) NOT NULL,
  `date` int(11) NOT NULL,
  `error` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `deltasole`
--
ALTER TABLE `deltasole`
  ADD PRIMARY KEY (`index`);

--
-- Indizes für die Tabelle `deltasole_wmz`
--
ALTER TABLE `deltasole_wmz`
  ADD PRIMARY KEY (`index`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `deltasole`
--
ALTER TABLE `deltasole`
  MODIFY `index` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `deltasole_wmz`
--
ALTER TABLE `deltasole_wmz`
  MODIFY `index` int(11) NOT NULL AUTO_INCREMENT;

CREATE USER 'heating'@'localhost' IDENTIFIED BY 'heating'; 
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, DROP, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON heating.* TO 'heating'@'localhost';
FLUSH PRIVILEGES;
