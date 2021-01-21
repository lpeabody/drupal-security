<?php

namespace Drush\Commands\AlterCommands;

use Consolidation\AnnotatedCommand\AnnotationData;
use Consolidation\AnnotatedCommand\CommandData;
use Consolidation\AnnotatedCommand\CommandResult;
use Drush\Commands\DrushCommands;
use Symfony\Component\Console\Command\Command;

/**
 * Class SecurityAlterCommands.
 *
 * @package Drush\Commands\AlterCommands
 */
class SecurityAlterCommands extends DrushCommands {

  /**
   * Alter the security command to allow exceptions to the security list.
   *
   * @hook alter pm:security
   * @usage drush pm:security --allowed='my/package:1.0.0,other/package:3.0.0-rc1'
   */
  public function alterSecurityCommand(CommandResult $result = NULL, CommandData $command_data = NULL) {
    if (!empty($result) && !empty($command_data)) {
      $allowed = $command_data->input()->getOption('allowed');
      /** @var \Consolidation\OutputFormatters\StructuredData\RowsOfFields|null $output */
      $flagged_packages = $result->getOutputData();
      $this->filterAllowedPackages($flagged_packages, $allowed);
      if (!count($flagged_packages)) {
        $this->logger()->success("<info>Security updates detected, but all were explicitly flagged as safe.</info>");
        return TRUE;
      }
    }
  }

  /**
   * Add allowed option, for explicitly allowing exceptions to security check.
   *
   * @hook option pm:security
   */
  public function addSecurityCommandAllowedOption(Command $command, AnnotationData $annotationData) {
    $command->addOption(
      'allowed',
      '',
      DrushCommands::OPT,
      'Comma-separated list of my/package:semver pairs.');
  }

  /**
   * Ensure allowed option is an array if not already.
   *
   * @hook validate pm:security
   */
  public function validateAllowedOption(CommandData $command_data) {
    $options = $command_data->options();
    if (!empty($options['allowed']) && is_string($options['allowed'])) {
      // Convert to array so its consistent with the expected structure
      // if you were to set the option in drush.yml.
      $command_data->input()->setOption('allowed', explode(',', $options['allowed']));
    }
  }

  /**
   * Filter out flagged packages that are explicitly permitted.
   *
   * @param \ArrayObject $flagged_packages
   *   Packages flagged with a security advisory.
   * @param null|string $allowed_packages
   *   Explicit package versions permitted.
   */
  protected function filterAllowedPackages(\ArrayObject &$flagged_packages, $allowed_packages = NULL) {
    if (!empty($allowed_packages)) {
      foreach ($allowed_packages as $package) {
        list($package_name, $package_version) = explode(':', $package);
        if (!empty($flagged_packages[$package_name]) && $flagged_packages[$package_name]['version'] == $package_version) {
          unset($flagged_packages[$package_name]);
        }
      }
    }
  }

}
