<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package   profilefield_database
 * @copyright 2025 Eduardo Kraus https://eduardokraus.com/
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['add-new-category'] = 'Adicionar nova categoria';
$string['add-new-data'] = 'Adicionar novo registro';
$string['all_category'] = 'Todas as categorias';
$string['category-save-error'] = 'Campos 1 e 2 são obrigatórios';
$string['category_field'] = 'Nome do campo';
$string['category_field-delete'] = 'Deixe em branco para excluir este campo.';
$string['category_field-empty'] = 'Deixe em branco para ignorar o campo.';
$string['category_field-error'] = 'O nome do campo é obrigatório';
$string['category_field-info'] = 'Informe o nome do campo que será exibido no formulário';
$string['category_field-placeholder'] = 'Nome do campo';
$string['category_field-title'] = 'Novo campo';
$string['category_name'] = 'Nome da categoria';
$string['category_name-error'] = 'O nome da categoria é obrigatório';
$string['category_name-info'] = 'Informe o nome da categoria que deseja utilizar';
$string['category_name-placeholder'] = 'Nome da categoria';
$string['category_none'] = 'Nenhuma categoria cadastrada!';
$string['confirm-delete-category'] = 'Deseja realmente excluir a categoria <strong>{$a}</strong>?';
$string['confirm-delete-data'] = 'Deseja realmente excluir o registro <strong>{$a->name}</strong>: <em>{$a->value}</em>?';
$string['data_none'] = 'Nenhum dado cadastrado!';
$string['delete-category'] = 'Excluir a categoria';
$string['delete-category-usedata'] = 'Esta categoria está sendo usada no campo <a href="{$a->wwwroot}/user/profile/index.php" target="_blank">{$a->name}</a>';
$string['delete-data'] = 'Excluir o registro';
$string['delete-data-usedata'] = 'Este registro está sendo usada pelo usuário <a href="{$a->wwwroot}/user/profile.php?id=?id={$a->id}" target="_blank">{$a->name}</a>';
$string['delete-success'] = 'Categoria excluída com sucesso';
$string['field_type'] = 'Tipo de campo';
$string['field_type-date'] = 'Dado será Data';
$string['field_type-info'] = 'Selecione o tipo de campo que será utilizado';
$string['field_type-int'] = 'Dado será Número';
$string['field_type-text'] = 'Dado será texto';
$string['manage-category'] = 'Para selecionar uma categoria, é necessário primeiro criar e gerenciar as categorias. Você pode fazer acessando a área <a href="/user/profile/field/database/category.php">Todas as categorias</a>.';
$string['missing-value'] = 'Campo "Selecione a categoria" precisa ser um valor válido em <a href="/user/profile/field/database/category.php">Todas as categorias</a>.';
$string['new-category'] = 'Nova categoria';
$string['new-data'] = 'Novo registro';
$string['pluginname'] = 'User Field DB';
$string['privacy:metadata'] = 'O plugin de banco de dados não armazena dados pessoais, e não envia para sites externos.';
$string['report_category_description'] = 'Este relatório exibe informações detalhadas sobre os alunos os vínculos do perfil "{$a}".';
$string['report_category_title'] = 'Relatório de alunos no perfil "{$a}"';
$string['select-category'] = 'Selecione a categoria';
