{extends file="index.tpl"}
{block name="content"}
    <section class="info-pro col-lg-8">
        <div class="info-pro-user">
            <img src="{$user_info[n].imgs}">
            <span>{$user_info[n].name}</span>
        </div>
        <div class="info-pro-all">
            <div class="info-pro-content">
                <h2>{$question.title}</h2>

                <p>{$question.content}</p>

                <div class="panel-body panel-pro-content">
                    <a href="..."><span class="glyphicon glyphicon-plus content-follow">关注</span></a>
                    <a href="..."><span class="glyphicon glyphicon-comment content-comment">评论</span></a>
                </div>
            </div>
            <hr>
        </div>


        <div class="info-pro-review">
            {section name =n loop=$answers}
            {section name=m loop=$answers[n]}
            <div class="info-pro-user">
                <img src="...">
                <span>昵称</span>
            </div>
            <div class="review-content">
                <p>{$answers[n][m].content}</p>
            </div>
            <div class="panel-body panel-review-body">
                <a href="..."><span class="glyphicon glyphicon-plus content-follow">追加回复</span></a>
                <a href="..."><span class="glyphicon glyphicon-thumbs-up content-follow">{$answers[n][m].vote_a}</span></a>
                <a href="..."><span class="glyphicon glyphicon-thumbs-down content-follow">{$answers[n][m].vate_d}</span></a>
            </div>
            
        </div>
        {/section}
        {/section}
    </section>
{/block}