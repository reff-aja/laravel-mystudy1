<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <span style="font-size: 24px; font-weight: bold; color: #4f46e5; text-decoration: none;">📝 SmartDo</span>
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>