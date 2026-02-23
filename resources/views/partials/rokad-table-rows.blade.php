@forelse($lagas as $purchaser)
    <tr>
        <td><span class="badge-mandi badge-info" style="font-family: monospace; font-size: 0.9rem;">{{ $purchaser->code }}</span></td>
        <td class="urdu-text" style="font-weight: 600;">{{ $purchaser->name }}</td>
        <td>{{ $purchaser->mobile }}</td>
        <td style="color: #475569;">Rs. {{ number_format($purchaser->total_dues, 2) }}</td>
        <td style="color: #059669;">Rs. {{ number_format($purchaser->total_paid, 2) }}</td>
        <td style="font-weight: 800; color: {{ $purchaser->balance > 0 ? '#ef4444' : '#10b981' }}">
            Rs. {{ number_format($purchaser->balance, 2) }}
        </td>
        <td>
            @if($purchaser->balance <= 0)
                <span class="badge-mandi badge-success">{{ __('ادا شدہ') }}</span>
            @elseif($purchaser->total_paid > 0)
                <span class="badge-mandi badge-warning">{{ __('جزوی') }}</span>
            @else
                <span class="badge-mandi badge-danger">{{ __('بقایا') }}</span>
            @endif
        </td>
        <td style="text-align: center;">
            <a href="{{ route('rokad', ['code_search' => $purchaser->code]) }}" class="btn-mandi btn-secondary-mandi" style="padding: 6px 12px;" title="{{ __('تفصیل دیکھیں') }}">
                <i class="fa fa-eye"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">
            <i class="fa fa-folder-open" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
            {{ __('کوئی ریکارڈ نہیں ملا') }}
        </td>
    </tr>
@endforelse
