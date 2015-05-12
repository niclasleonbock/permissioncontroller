<?php namespace Bock\PermissionController;

use Backend\Classes\Controller as BaseController;
use Response;
use View;
use Flash;

class Controller extends BaseController
{
    /**
     * @var array Associative array of permissions required to use different actions of this controller.
     */
    private $_requiredPermissions = [];

    /**
     * {@inheritDoc}
     */
    public function __construct($action = null, $params = [])
    {
        parent::__construct();

        $this->_requiredPermissions = $this->requiredPermissions;
        $this->requiredPermissions = null;
    }

    /**
     * Gets the required permission for an action (e.g. `delete`).
     * @param   string  $action The action name.
     * @return  string  The permission.
     */
    public function getRequiredPermission($action)
    {
        return isset($this->_requiredPermissions[$action]) ?
            $this->_requiredPermissions[$action] : null;
    }

    /**
     * Alias for `$this->user->hasAccess`. Checks if the authenticated user has a given 
     * @param   string  $permission The permission name.
     * @return  boolean
     */
    public function isAllowed($permission)
    {
        // no permission means no need to restrict any access
        if ($permission == null) {
            return true;
        }

        return $this->user->hasAccess($permission);
    }

    /**
     * Checks wether the action is allowed in this request cycle, based on the user permissions.
     * @param   string  $action The action name.
     * @return  boolean
     */
    public function can($action)
    {
        return $this->isAllowed($this->getRequiredPermission($action));
    }

    /**
     * Ends the current request with a not-allowed message.
     * Either as flash message (if `$flash` is set to `true`) or an error page.
     * @param   boolean  $flash Set to true if the request should be terminated with a flash message.
     * @return  boolean
     */
    public function notAllowed($flash = false)
    {
        if ($flash) {
            return Flash::error(trans('backend::lang.page.access_denied.help'));
        }

        return Response::make(View::make('backend::access_denied'), 403);
    }

    /**
     * {@inheritDoc}
     */
    public function listExtendColumns($host)
    {
        if (!$this->can('index')) {
            $host->showCheckboxes = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function runAjaxHandler($handler)
    {
        if (
            ($handler == 'onSave' && !$this->can($this->action) ||
            (($handler == 'onDelete' || $this->action == 'delete') && !$this->can('delete')))
        ) {
                return $this->notAllowed(true);
        }

        return parent::runAjaxHandler($handler);
    }

    /**
     * {@inheritDoc}
     */
    protected function execPageAction($actionName, $parameters)
    {
        if (!$this->can($actionName)) {
            return $this->notAllowed();
        }

        return parent::execPageAction($actionName, $parameters);
    }
}

