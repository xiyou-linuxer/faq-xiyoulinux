{extends file="index.tpl"}
{block name="content"}
    <div class="col-lg-8">
        <div class="content  program" style="padding-bottom: 30px; padding-right: 30px;">
            <form action="addquestion.php" method="post">
                <input type="title" class="form-control titleclass" id="title" placeholder="标题">
                <textarea id="content" class="form-control contentclass" rows="20" placeholder="内容"></textarea>
                <input type="tags" class="form-control tagsclass" id="tags" placeholder="标签">

                <div style="text-align:right">
                    <button type="button" class="btn btn-info submitclass">发表</button>
                </div>
            </form>
        </div>
    </div>
{/block}

{block name="script"}
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script>
        var content = new Simditor({
            textarea: $('#content')
        });

    </script>
{/block}