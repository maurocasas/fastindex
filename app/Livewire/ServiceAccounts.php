<?php

namespace App\Livewire;

use App\Events\ServiceAccounts\Created;
use App\Events\ServiceAccounts\Removed;
use App\Models\ServiceAccount;
use App\Models\Site;
use App\Services\GoogleClientFactory;
use Google\Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class ServiceAccounts extends Component
{
    use WithFileUploads, WithPagination;

    public $credentials;

    public function destroy(ServiceAccount $serviceAccount)
    {
        foreach (['service_account_sites', 'service_account_logs'] as $table) {
            DB::table($table)
                ->where('service_account_id', $serviceAccount->id)
                ->delete();
        }

        event(new Removed($serviceAccount));

        $serviceAccount->delete();

        Toaster::success('Service account deleted.');
    }

    /**
     * @throws Exception
     */
    public function store()
    {

        $this->validate([
            'credentials' => ['required', 'file', 'mimes:json'],
        ]);

        /** @var UploadedFile $file */
        $file = $this->credentials;

        $checksum = md5($file->getContent());

        if (ServiceAccount::where('checksum', $checksum)->exists()) {
            $this->addError('credentials', 'Service account already linked.');

            return;
        }

        $contents = @json_decode($file->getContent());

        $keys = [
            'type',
            'project_id',
            'private_key_id',
            'private_key',
            'client_email',
            'client_id',
        ];

        if ($contents?->type !== 'service_account') {
            $this->addError('credentials', 'Only service_account credentials are accepted.');

            return;
        }

        foreach ($keys as $key) {
            if (blank($contents?->$key ?? null)) {
                $this->addError('credentials', 'Credentials JSON is not valid. Upload as is.');

                return;
            }
        }

        $serviceAccount = new ServiceAccount;
        $serviceAccount->credentials = $contents;

        $clientFactory = app(GoogleClientFactory::class);
        $clientFactory->boot($serviceAccount);

        if (! $clientFactory->validate()) {
            $this->addError('credentials', 'Credentials cannot be validated with Google API.');

            return;
        }

        $serviceAccount->validated_at = now();
        $serviceAccount->save();

        $this->dispatch('close-modal');

        Toaster::success('Service account successfully linked.');

        event(new Created($serviceAccount));
    }

    #[Title('Service accounts')]
    public function render()
    {
        /**
         * Explicitly ignoring this message because there's a discrepancy when
         * performing withCount using SQLite and MySQL.
         */

        // @phpstan-ignore-next-line
        $risk = Site::withCount('service_accounts')
            ->having('service_accounts_count', '>', 1)
            ->get()
            ->count();

        $serviceAccounts = ServiceAccount::latest()
            ->withCount('sites')
            ->withCount(['logs' => function ($query) {
                $query->where('created_at', '>=', now()->subDay());
            }])
            ->paginate(24);

        return view('livewire.service-accounts')
            ->with(compact('serviceAccounts', 'risk'));
    }
}
