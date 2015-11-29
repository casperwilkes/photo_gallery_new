<h2>Manage Users</h2>

<p><?= Html::anchor('admin', 'Back to Admin'); ?></p>

<table id="users_table">
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Group</th>
            <th>Last Login</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->username; ?></td>
                <td><?= $user->email; ?></td>
                <td><?= $group->get_name($user->group); ?></td>
                <td><?= Helper::date_info($user->last_login, 'date_time_short'); ?></td>
                <td><?= Html::anchor('admin/users_edit/' . $user->id, 'Edit'); ?></td>
                <td><?= Html::anchor('admin/users_delete/' . $user->id, 'Delete'); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>