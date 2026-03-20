@extends('material.layouts.app')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">{{ $title ?? 'Contact Submissions' }}</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="col-6 ms-md-auto pe-md-3 d-flex align-items-center">
                            <form action="{{ route('contact-submissions.index') }}" method="GET" class="w-100">
                                <div class="input-group input-group-outline">
                                    <label class="form-label">Search by name, email, phone, or message...</label>
                                    <input name="search" type="text" class="form-control"
                                        value="{{ request('search') }}" onfocus="focused(this)"
                                        onfocusout="defocused(this)">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Email</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Phone</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Message</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Source</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($submissions as $submission)
                                        <tr>
                                            <td class="px-3">
                                                <p class="text-xs font-weight-bold mb-0">{{ $submission->name }}</p>
                                            </td>
                                            <td class="px-3">
                                                <p class="text-xs mb-0">{{ $submission->email }}</p>
                                            </td>
                                            <td class="px-3">
                                                <p class="text-xs mb-0">{{ $submission->phone ?? 'N/A' }}</p>
                                            </td>
                                            <td class="px-3">
                                                <p class="text-xs mb-0" style="max-width: 380px; white-space: normal;">
                                                    {{ $submission->message }}
                                                </p>
                                            </td>
                                            <td class="px-3">
                                                <span class="badge badge-sm bg-gradient-info text-capitalize">
                                                    {{ $submission->source ?? 'website' }}
                                                </span>
                                            </td>
                                            <td class="px-3">
                                                <p class="text-xs mb-0">
                                                    {{ optional($submission->created_at)->format('d M Y, h:i A') }}</p>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-sm text-secondary">
                                                No contact submissions found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-4 px-3">{{ $submissions->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
