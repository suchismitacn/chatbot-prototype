@extends('email.master')
@section('content')
<table width="640" border="0" align="center" cellpadding="0" cellspacing="0" class="body-wrap">
    <tbody>
        <tr>
            <td align="center" bgcolor="#EB5B25">
                <h1>Your converstation with the bot</h1>
            </td>
        </tr>
    </tbody>
</table>

<table width="640" border="0" align="center" cellpadding="0" cellspacing="0" class="body-wrap">
    <tbody>
        <tr>
            <td align="center" bgcolor="#FFFFFF">
                <table width="640" border="0" align="center" cellpadding="0" cellspacing="0" class="body-wrap">
                    <tbody>
                        <tr>
                            <td align="center" bgcolor="#FFFFFF" style="padding: 15px 0px 30px 0px;">
                                <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0"
                                    style="max-width: 500px;">
                                    <tbody>
                                        <tr>
                                            <td align="center"
                                                style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #111111;line-height:22px;text-align:left;">
                                                @if (!is_null($conversation))
                                                <div style="overflow-y: scroll; ">
                                                    <ol class="chat" style="height: 390px;">
                                                        @foreach ($conversation as $key => $entry)
                                                        <li class="{{ ($entry->sender == 'bot') ? 'chatbot' : 'visitor'}}"
                                                            style="">
                                                            <div class="msg">
                                                                <div>
                                                                    <div>{{ $entry->content }}</div>
                                                                    @if (!is_null($entry->optional_data))
                                                                    <div>
                                                                    @foreach ($entry->optional_data as $option)
                                                                        <div class="btn">{{ $option }}</div>
                                                                    @endforeach
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                <div class="time">{{ $entry->created_at->format('H:i') }}</div>
                                                            </div>
                                                        </li>
                                                        @endforeach
                                                    </ol>
                                                </div>
                                                @else
                                                    Sorry! No conversation.
                                                @endif
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" style="max-width: 500px;">
                    <tbody>
                        <tr>
                            <td height="20" align="center" style="border-top: 2px solid #666666;">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
@endsection
