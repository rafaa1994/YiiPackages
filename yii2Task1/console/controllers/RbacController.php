<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use console\models\AdminRule;

class RbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;

        // add the rule
        $rule = new AdminRule();
        $auth->add($rule);

        // add "update" permission
        $updateOwnAccount = $auth->createPermission('updateOwnAccount');
        $updateOwnAccount->description = 'Update Own Account';
        $updateOwnAccount->ruleName = $rule->name;
        $auth->add($updateOwnAccount);

        // add "author" role and give this role the "createPost" permission
        $admin = $auth->createRole('Admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateOwnAccount);


        // add "updateAsRoot" permission
        $updateAsRoot = $auth->createPermission('updateAsRoot');
        $updateAsRoot->description = 'Update All Accounts as Root';
        $auth->add($updateAsRoot);

        $auth->addChild($updateAsRoot, $updateOwnAccount);

        // add "root" role and give this role the "update" permission
        // as well as the permissions of the "author" role, and "updateAsRoot" permission
        $root = $auth->createRole('Root');
        $auth->add($root);
        $auth->addChild($root, $admin);
        $auth->addChild($root, $updateAsRoot);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($root, 2);
        $auth->assign($admin, 1);
    }

}
