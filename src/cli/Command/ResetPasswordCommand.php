<?php
declare(strict_types=1);

namespace GuldenWallet\CLI\Command;

use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Throwable;

class ResetPasswordCommand extends Command
{
    /** @var string */
    const GREEN_TERMINAL_COLOR = "\e[32m";

    /** @var string */
    const NO_TERMINAL_COLOR = "\e[0m";

    /** @var string */
    const RED_TERMINAL_COLOR = "\e[31m";

    /** @var PDO */
    private $databaseConnection;

    /**
     * @param PDO $databaseConnection
     */
    public function __construct(PDO $databaseConnection)
    {
        parent::__construct();

        $this->databaseConnection = $databaseConnection;
    }

    /**
     * @return void
     */
    public function configure()
    {
        $this
            ->setName('reset-password');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$input->isInteractive()) {
            $output->writeln($this->color('Resetting a password only works though an interactive session', 'red'));

            return 1;
        }

        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        if (!$this->askForNewPassword($questionHelper, $input, $output)) {
            return 1;
        };

        return $this->askToRevokeAllActiveTokens($questionHelper, $input, $output) ? 0 : 1;
    }

    /**
     * @param QuestionHelper $questionHelper
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    private function askForNewPassword(
        QuestionHelper $questionHelper,
        InputInterface $input,
        OutputInterface $output
    ): bool {
        $initialPasswordQuestion = (new Question($this->color('Enter a new password (6+ characters): ', 'green')))
            ->setHidden(true);

        $password = null;

        while (!$password || strlen($password) < 6) {
            $password = trim($questionHelper->ask($input, $output, $initialPasswordQuestion));
        };

        $confirmPasswordQuestion = (new Question($this->color('Confirm password: ', 'green'), null))->setHidden(true);

        $confirmationPassword = trim($questionHelper->ask($input, $output, $confirmPasswordQuestion));

        $output->writeln('');

        if ($confirmationPassword !== $password) {
            $output->writeln($this->color('Passwords did not match. Password was not changed.', 'red'));

            return false;
        }

        if (!$this->updatePassword($password)) {
            $output->writeln($this->color('Password could not be updated due to a technical issue.', 'red'));

            return false;
        }

        $output->writeln('Password was successfully updated.');

        return true;
    }

    /**
     * @param QuestionHelper $questionHelper
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    private function askToRevokeAllActiveTokens(
        QuestionHelper $questionHelper,
        InputInterface $input,
        OutputInterface $output
    ): bool {
        $output->writeln('');

        $revokeQuestion = (new Question($this->color(
            'Do you want to revoke all access tokens (end all current sessions) [y|n]? ', 'green'), 'y'
        ))->setNormalizer('strtolower');

        $answer = $questionHelper->ask($input, $output, $revokeQuestion);

        if ($answer === 'y') {
            $success = $this->revokeAllTokens();

            $output->writeln('');

            if ($success) {
                $output->writeln('All access tokens have been revoked and sessions ended.');

                return true;
            } else {
                $output->writeln($this->color('Access tokens could not be revoked.', 'red'));

                return false;
            }
        }

        return true;
    }

    /**
     * @param string $text
     * @param string $color
     * @return string
     */
    private function color(string $text, string $color): string
    {
        switch ($color) {
            case 'green':
                $color = self::GREEN_TERMINAL_COLOR;
                break;
            case 'red':
                $color = self::RED_TERMINAL_COLOR;
                break;
            default:
                $color = self::NO_TERMINAL_COLOR;
        }

        return $color . $text . self::NO_TERMINAL_COLOR;
    }

    /**
     * @return bool
     */
    private function revokeAllTokens(): bool
    {
        try {
            $this->databaseConnection->query("
                DELETE FROM access_tokens
            ");

            return true;
        } catch (Throwable $throwable) {
            echo $throwable->getMessage();

            return false;
        }
    }

    /**
     * @param string $password
     * @return bool
     */
    private function updatePassword(string $password): bool
    {
        try {
            $statement = $this->databaseConnection->prepare("
                INSERT INTO user_details
                  (SETTING_KEY, SETTING_VALUE)
                VALUES ('PASSWORD_HASH', :passwordHash)
                ON DUPLICATE KEY UPDATE SETTING_VALUE = VALUES(SETTING_VALUE)
            ");

            $statement->execute([
                ':passwordHash' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12])
            ]);

            return true;
        } catch (Throwable $throwable) {
            return false;
        }
    }
}
