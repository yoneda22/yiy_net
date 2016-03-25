<table>
    <tr>
        <th>名前</th>
        <td><span id="profile_name">{$profile.name}</span></td>
    </tr>
    <tr>
        <th>生年月日</th>
        <td><span id="profile_birthday">{$profile.birthday}</span></td>
    </tr>
    <tr>
        <th>住所</th>
        <td>{$profile.address_label}</td>
    </tr>
    <tr>
        <th>最寄り駅</th>
        <td><span id="profile_station">{$profile.station}</span></td>
    </tr>
<!--
    <tr>
        <th>強調スキル</th>
        <td></td>
    </tr>
-->
</table>
<input type="hidden" id="user_id"value="{$profile.id}" />
<input type="hidden" id="profile_address_value" value="{$profile.address_value}" />
