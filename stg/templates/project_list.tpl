<input type="hidden" id="f-project-skills" value="{$skills}" />
{if $projects != null}
<table>
    <tr>
        <th class="titlecheck">有効/無効</th>
        <th class="titleterm">期間</th>
        <th class="title">プロジェクト名</th>
        <th class="title">スキル</th>
        <th class="w12p"></th>
    </tr>
{foreach from=$projects key=mykey item=project}
    <tr>
    {if $project.available_flag == 1}
        <th class="on">
            <input id="ava_{$project.project_id}" type="checkbox" checked disabled>
    {else}
        <th class="off">
            <input id="ava_{$project.project_id}" type="checkbox" disabled>
    {/if}
        </th>
        <td>{$project.start_label}<br /> ～ {$project.end_label}</td>
        <td>{$project.name}</td>
        <td>{foreach from=$project.skills key=mykey item=skill}{$skill.skill_name}, {/foreach}</td>
        <th><button id="imageBtn05" class="imghover project_edit" value="{$project.project_id}">編集</button></th>
        <input type="hidden" id="start_{$project.project_id}"  value="{$project.start}" />
        <input type="hidden" id="end_{$project.project_id}"    value="{$project.end}" />
        <input type="hidden" id="name_{$project.project_id}"   value="{$project.name}" />
        <input type="hidden" id="note_{$project.project_id}"   value="{$project.note}" />
        <input type="hidden" id="skills_{$project.project_id}" value="{foreach from=$project.skills key=mykey item=skill}{$skill.skill_id},{/foreach}" />
    </tr>
{/foreach}
</table>
{else}
<br />
<div>プロジェクト情報が登録されていません。</div>
{/if}
