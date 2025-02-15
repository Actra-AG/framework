<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\session;

class FileSessionHandler extends AbstractSessionHandler
{
    public function __construct(private readonly SessionSettingsModel $sessionSettingsModel)
    {
        parent::__construct(sessionSettingsModel: $sessionSettingsModel);
    }

    protected function executePreStartActions(): void
    {
        if ($this->sessionSettingsModel->savePath !== '') {
            session_save_path(path: $this->sessionSettingsModel->savePath);
        }
    }
}