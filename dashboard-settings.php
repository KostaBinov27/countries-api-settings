<div id="wpbody" role="main">
    <div id="wpbody-content">
        <h1>General Settings</h1>
        <form method="post" id="change_calc_settings" enctype="multipart/form-data">
            <h3 class="descriptionString">Please specify how often the data should be refreshed from the API. (If blank 60 minutes)</h3>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label>Time Limit in minutes</label>
                        </th>
                        <td>
                            <input name="timelimit" type="number" min="1" class="timelimit" value="<?php echo get_option('timelimit') ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <input type="submit" name="saveLimit" id="saveLimit" class="button button-primary">
                        </th>
                    </tr>
                </tbody>
            </table>
        </form>
        <div class="responseTest">
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label>Test link:</label>
                        </th>
                        <td>
                            <input type="text" class="apilink" value="https://countries.trevorblades.com/graphql">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <button id="checkResponse" class="button button-primary">Check</button>
                        </th>
                    </tr>
                </tbody>
            </table>
            <div class="codeViewrResponse">
                <code class="resposne"></code>
            </div>
        </div>
    </div>
</div>