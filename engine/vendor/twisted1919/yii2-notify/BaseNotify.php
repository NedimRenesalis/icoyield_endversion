<?php

namespace twisted1919\notify;

use yii\base\Component;
use yii\helpers\Html;

class BaseNotify extends Component
{
    /**
     * flag for errors
     */
    const ERROR = 'error';

    /**
     * flag for warnings
     */
    const WARNING = 'warning';

    /**
     * flag for info
     */
    const INFO = 'info';

    /**
     * flag for success
     */
    const SUCCESS = 'success';

    /**
     * @var string
     */
    public $errorClass = 'alert alert-block alert-danger';

    /**
     * @var string
     */
    public $warningClass = 'alert alert-block alert-warning';

    /**
     * @var string
     */
    public $infoClass = 'alert alert-block alert-info';

    /**
     * @var string
     */
    public $successClass = 'alert alert-block alert-success';

    /**
     * @var string
     */
    public $htmlWrapper = '<div class="%s">%s</div>';

    /**
     * @var string
     */
    public $htmlCloseButton = '<button type="button" class="close" data-dismiss="alert">&times;</button>';

    /**
     * @var string
     */
    public $htmlHeading = '<p>%s</p>';

    /**
     * @var array
     */
    protected $cliMessages = [];

    /**
     * @return string
     */
    public function show()
    {
        $output = '';

        if ($this->getIsCli()) {
            foreach (array(self::ERROR, self::WARNING, self::INFO, self::SUCCESS) as $type) {
                if (!empty($this->cliMessages[$type])) {
                    foreach ($this->cliMessages[$type] as $index => $message) {
                        $output .= ucfirst($type) . ': ' . strip_tags($message) . "\n";
                    }
                    $this->cliMessages[$type] = [];
                }
            }
            return $output;
        }

        $error   = session()->getFlash('notify.error', []);
        $warning = session()->getFlash('notify.warning', []);
        $info    = session()->getFlash('notify.info', []);
        $success = session()->getFlash('notify.success', []);

        $error   = is_array($error)   ? array_unique($error)   : [];
        $warning = is_array($warning) ? array_unique($warning) : [];
        $info    = is_array($info)    ? array_unique($info)    : [];
        $success = is_array($success) ? array_unique($success) : [];

        if (count($error) > 0) {
            $liItems = [];
            foreach ($error as $message) {
                $liItems[] = Html::tag('li', $message, []);
            }
            $ul = Html::tag('ul', implode("\n", $liItems), []);

            $content = '';
            if ($this->htmlCloseButton) {
                $content.= $this->htmlCloseButton;
            }
            if (($heading = $this->getErrorHeading()) && $this->htmlHeading) {
                $content.= sprintf($this->htmlHeading, $heading);
            }
            $content.= $ul;
            $output .= sprintf($this->htmlWrapper, $this->errorClass, $content);
        }

        if (count($warning) > 0) {
            $liItems = [];
            foreach ($warning as $message) {
                $liItems[] = Html::tag('li', $message, []);
            }
            $ul = Html::tag('ul', implode("\n", $liItems), []);

            $content = '';
            if ($this->htmlCloseButton) {
                $content.= $this->htmlCloseButton;
            }
            if (($heading = $this->getWarningHeading()) && $this->htmlHeading) {
                $content.= sprintf($this->htmlHeading, $heading);
            }
            $content.= $ul;
            $output .= sprintf($this->htmlWrapper, $this->warningClass, $content);
        }

        if (count($info) > 0) {
            $liItems = [];
            foreach ($info as $message) {
                $liItems[] = Html::tag('li', $message, []);
            }
            $ul = Html::tag('ul', implode("\n", $liItems), []);

            $content = '';
            if ($this->htmlCloseButton) {
                $content.= $this->htmlCloseButton;
            }
            if (($heading = $this->getInfoHeading()) && $this->htmlHeading) {
                $content.= sprintf($this->htmlHeading, $heading);
            }
            $content.= $ul;
            $output .= sprintf($this->htmlWrapper, $this->infoClass, $content);
        }

        if (count($success) > 0) {
            $liItems = [];
            foreach ($success as $message) {
                $liItems[] = Html::tag('li', $message, []);
            }
            $ul = Html::tag('ul', implode("\n", $liItems), []);

            $content = '';
            if ($this->htmlCloseButton) {
                $content.= $this->htmlCloseButton;
            }
            if (($heading = $this->getSuccessHeading()) && $this->htmlHeading) {
                $content.= sprintf($this->htmlHeading, $heading);
            }
            $content.= $ul;
            $output .= sprintf($this->htmlWrapper, $this->successClass, $content);
        }

        return $output;
    }

    /**
     * @param $message
     * @param string $type
     * @return mixed
     */
    public function add($message, $type = self::WARNING)
    {
        $map = [
            self::ERROR   => 'addError',
            self::WARNING => 'addWarning',
            self::INFO    => 'addInfo',
            self::SUCCESS => 'addSuccess',
        ];

        if (!in_array($type, array_keys($map))) {
            $type = self::WARNING;
        }

        return call_user_func(array($this, $map[$type]), $message);
    }

    /**
     * @param $message
     * @return $this
     */
    public function addError($message)
    {
        if (!is_array($message)) {
            $message = [$message];
        }

        if ($this->getIsCli()) {
            if (!isset($this->cliMessages[self::ERROR])) {
                $this->cliMessages[self::ERROR] = [];
            }
            $this->cliMessages[self::ERROR] = array_merge($this->cliMessages[self::ERROR], $message);
            return $this;
        }

        $flash = session()->getFlash('notify.error', [], false);
        $flash = array_merge($flash, $message);
        session()->setFlash('notify.error', $flash);

        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    public function addWarning($message)
    {
        if (!is_array($message)) {
            $message = [$message];
        }

        if ($this->getIsCli()) {
            if (!isset($this->cliMessages[self::WARNING])) {
                $this->cliMessages[self::WARNING] = [];
            }
            $this->cliMessages[self::WARNING] = array_merge($this->cliMessages[self::WARNING], $message);
            return $this;
        }

        $flash = session()->getFlash('notify.warning', [], false);
        $flash = array_merge($flash, $message);
        session()->setFlash('notify.warning', $flash);

        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    public function addInfo($message)
    {
        if (!is_array($message)) {
            $message = [$message];
        }

        if ($this->getIsCli()) {
            if (!isset($this->cliMessages[self::INFO])) {
                $this->cliMessages[self::INFO] = [];
            }
            $this->cliMessages[self::INFO] = array_merge($this->cliMessages[self::INFO], $message);
            return $this;
        }

        $flash = session()->getFlash('notify.info', [], false);
        $flash = array_merge($flash, $message);
        session()->setFlash('notify.info', $flash);

        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    public function addSuccess($message)
    {
        if (!is_array($message)) {
            $message = [$message];
        }

        if ($this->getIsCli()) {
            if (!isset($this->cliMessages[self::SUCCESS])) {
                $this->cliMessages[self::SUCCESS] = [];
            }
            $this->cliMessages[self::SUCCESS] = array_merge($this->cliMessages[self::SUCCESS], $message);
            return $this;
        }

        $flash = session()->getFlash('notify.success', [], false);
        $flash = array_merge($flash, $message);
        session()->setFlash('notify.success', $flash);

        return $this;
    }

    /**
     * @return $this
     */
    public function clearError()
    {
        if ($this->getIsCli()) {
            $this->cliMessages[self::ERROR] = [];
            return $this;
        }
        session()->setFlash('notify.error', []);
        return $this;
    }

    /**
     * @return $this
     */
    public function clearWarning()
    {
        if ($this->getIsCli()) {
            $this->cliMessages[self::WARNING] = [];
            return $this;
        }
        session()->setFlash('notify.warning', []);
        return $this;
    }

    /**
     * @return $this
     */
    public function clearInfo()
    {
        if ($this->getIsCli()) {
            $this->cliMessages[self::INFO] = [];
            return $this;
        }
        session()->setFlash('notify.info', []);
        return $this;
    }

    /**
     * @return $this
     */
    public function clearSuccess()
    {
        if ($this->getIsCli()) {
            $this->cliMessages[self::SUCCESS] = [];
            return $this;
        }
        session()->setFlash('notify.success', []);
        return $this;
    }

    /**
     * @return $this
     */
    public function clearAll()
    {
        return $this->clearError()->clearWarning()->clearInfo()->clearSuccess();
    }

    /**
     * @param $text
     * @return $this
     */
    public function setErrorHeading($text)
    {
        if ($this->getIsCli()) {
            return $this;
        }
        session()->setFlash('notify.error_heading', $text);
        return $this;
    }

    /**
     * 
     */
    public function getErrorHeading()
    {
        if ($this->getIsCli()) {
            return;
        }
        return session()->getFlash('notify.error_heading');
    }

    /**
     * @param $text
     * @return $this
     */
    public function setWarningHeading($text)
    {
        if ($this->getIsCli()) {
            return $this;
        }
        session()->setFlash('notify.warning_heading', $text);
        return $this;
    }

    /**
     * @return string
     */
    public function getWarningHeading()
    {
        if ($this->getIsCli()) {
            return;
        }
        return session()->getFlash('notify.warning_heading');
    }

    /**
     * @param $text
     * @return $this
     */
    public function setInfoHeading($text)
    {
        if ($this->getIsCli()) {
            return $this;
        }
        session()->setFlash('notify.info_heading', $text);
        return $this;
    }

    /**
     * @return string
     */
    public function getInfoHeading()
    {
        if ($this->getIsCli()) {
            return;
        }
        return session()->getFlash('notify.info_heading');
    }

    /**
     * @param $text
     * @return $this
     */
    public function setSuccessHeading($text)
    {
        if ($this->getIsCli()) {
            return $this;
        }
        session()->setFlash('notify.success_heading', $text);
        return $this;
    }

    /**
     * @return string
     */
    public function getSuccessHeading()
    {
        if ($this->getIsCli()) {
            return;
        }
        return session()->getFlash('notify.success_heading');
    }

    /**
     * @return bool
     */
    public function getHasSuccess()
    {
        if ($this->getIsCli()) {
            return !empty($this->cliMessages[self::SUCCESS]);
        }
        $messages = session()->getFlash('notify.success', [], false);
        return !empty($messages);
    }

    /**
     * @return bool
     */
    public function getHasInfo()
    {
        if ($this->getIsCli()) {
            return !empty($this->cliMessages[self::INFO]);
        }
        $messages = session()->getFlash('notify.info', [], false);
        return !empty($messages);
    }

    /**
     * @return bool
     */
    public function getHasWarning()
    {
        if ($this->getIsCli()) {
            return !empty($this->cliMessages[self::WARNING]);
        }
        $messages = session()->getFlash('notify.warning', [], false);
        return !empty($messages);
    }

    /**
     * @return bool
     */
    public function getHasError()
    {
        if ($this->getIsCli()) {
            return !empty($this->cliMessages[self::ERROR]);
        }
        $messages = session()->getFlash('notify.error', [], false);
        return !empty($messages);
    }

    /**
     * @return bool
     */
    public function getIsEmpty()
    {
        return !$this->getHasSuccess() && !$this->getHasInfo() && !$this->getHasWarning() && !$this->getHasError();
    }

    /**
     * @return bool
     */
    public function getIsCli()
    {
        return php_sapi_name() == 'cli' || (!isset($_SERVER['SERVER_SOFTWARE']) && !empty($_SERVER['argv']));
    }
}