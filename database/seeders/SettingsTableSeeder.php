<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        $settings = [

            // 🌐 General App Settings
            [
                'key' => 'app_name',
                'value' => 'Laravel Starter',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Application Name',
                'description' => 'The name of your application',
                'order' => 1
            ],
            [
                'key' => 'app_locale',
                'value' => 'en',
                'type' => 'select',
                'group' => 'general',
                'options' => json_encode([
                    'en' => 'English',
                    'es' => 'Spanish',
                    'fr' => 'French'
                ]),
                'label' => 'Default Language',
                'description' => 'The default language for the application',
                'order' => 2
            ],
            [
                'key' => 'app_timezone',
                'value' => 'UTC',
                'type' => 'select',
                'group' => 'general',
                'options' => json_encode(
                    collect(timezone_identifiers_list())->mapWithKeys(fn($tz) => [$tz => $tz])
                ),
                'label' => 'Timezone',
                'description' => 'The default timezone for the application',
                'order' => 3
            ],
            [
                'key' => 'app_logo',
                'value' => '',
                'type' => 'file',
                'group' => 'general',
                'label' => 'Application Logo',
                'description' => 'Upload the application logo (JPG, PNG, SVG)',
                'order' => 4
            ],

            // 👤 Profile Settings
            [
                'key' => 'profile_display_name_format',
                'value' => 'first_last',
                'type' => 'select',
                'group' => 'profile',
                'options' => json_encode([
                    'first_last' => 'First Last',
                    'last_first' => 'Last, First',
                    'username' => 'Username Only'
                ]),
                'label' => 'Name Display Format',
                'description' => 'How user names are displayed in the UI',
                'order' => 1
            ],

            // 🔐 Two-Factor Auth
            [
                'key' => '2fa_enforced',
                'value' => '0',
                'type' => 'boolean',
                'group' => '2fa',
                'label' => 'Enforce 2FA Globally',
                'description' => 'Require all users to enable two-factor authentication',
                'order' => 1
            ],

            // 💾 Backup Settings
            [
                'key' => 'backup_retention_days',
                'value' => '30',
                'type' => 'number',
                'group' => 'backup',
                'label' => 'Backup Retention (Days)',
                'description' => 'Number of days to retain backups before automatic deletion',
                'order' => 1
            ],
            [
                'key' => 'backup_notify_email',
                'value' => '',
                'type' => 'text',
                'group' => 'backup',
                'label' => 'Backup Notification Email',
                'description' => 'Email address to notify upon successful or failed backups',
                'order' => 2
            ],

            // 🔄 Job Queue Settings
            [
                'key' => 'job_retry_attempts',
                'value' => '3',
                'type' => 'number',
                'group' => 'queue',
                'label' => 'Default Job Retry Attempts',
                'description' => 'Number of retry attempts for failed jobs',
                'order' => 1
            ],

            // 🗂️ File Upload Settings
            [
                'key' => 'file_upload_limit',
                'value' => '10',
                'type' => 'number',
                'group' => 'files',
                'label' => 'File Upload Limit (MB)',
                'description' => 'Maximum file size allowed for upload in megabytes',
                'order' => 1
            ],
            [
                'key' => 'allowed_file_types',
                'value' => 'pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
                'type' => 'text',
                'group' => 'files',
                'label' => 'Allowed File Types',
                'description' => 'Comma-separated list of allowed file extensions',
                'order' => 2
            ],

            // 🔐 Security Settings
            [
                'key' => 'enable_encryption',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'security',
                'label' => 'Enable File Encryption',
                'description' => 'Allow users to encrypt files',
                'order' => 1
            ],
            [
                'key' => 'custom_encryption_key',
                'value' => 'base64:jLdG0lbfHNlwRlPFwxySOotDXJBASrnF4ORtvHOURaY=',
                'type' => 'text',
                'group' => 'security',
                'label' => 'Custom File Encryption Key',
                'description' => 'Allow users to encrypt and decrypt files using this key',
                'order' => 2
            ],

        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
