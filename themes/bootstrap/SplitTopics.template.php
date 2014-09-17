<?php

/**
 * @name      ElkArte Forum
 * @copyright ElkArte Forum contributors
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * This software is a derived product, based on:
 *
 * Simple Machines Forum (SMF)
 * copyright:	2011 Simple Machines (http://www.simplemachines.org)
 * license:  	BSD, See included LICENSE.TXT for terms and conditions.
 *
 * @version 1.0
 *
 */

/**
 * Generic reuse templates is where its at
 */
function template_SplitTopics_init()
{
	loadTemplate('GenericHelpers');
}

/**
 * Show an interface to ask the user the options for split topics.
 */
function template_ask()
{
	global $context, $txt, $scripturl;

	echo '
	<div id="split_topics">
		<form action="', $scripturl, '?action=splittopics;sa=execute;topic=', $context['current_topic'], '.0" method="post" accept-charset="UTF-8">
			<input type="hidden" name="at" value="', $context['message']['id'], '" />
			<h2 class="category_header">', $txt['split_topic'], '</h2>
			<div class="windowbg">
				<div class="content">
					<div class="split_topics">
						<p>
							<strong><label for="subname">', $txt['subject_new_topic'], '</label>:</strong>
							<input type="text" name="subname" id="subname" value="', $context['message']['subject'], '" size="25" class="input_text" autofocus="autofocus" />
						</p>
						<ul class="split_topics">
							<li>
								<input type="radio" id="onlythis" name="step2" value="onlythis" checked="checked" class="input_radio" /> <label for="onlythis">', $txt['split_this_post'], '</label>
							</li>
							<li>
								<input type="radio" id="afterthis" name="step2" value="afterthis" class="input_radio" /> <label for="afterthis">', $txt['split_after_and_this_post'], '</label>
							</li>
							<li>
								<input type="radio" id="selective" name="step2" value="selective" class="input_radio" /> <label for="selective">', $txt['select_split_posts'], '</label>
							</li>
						</ul>
						<hr />
						<label for="messageRedirect"><input type="checkbox" name="messageRedirect" id="messageRedirect" onclick="document.getElementById(\'reasonArea\').style.display = this.checked ? \'block\' : \'none\';" class="input_check" /> ', $txt['splittopic_notification'], '.</label>
						<fieldset id="reasonArea" style="display: none;', '">
							<dl class="settings">
								<dt>
									', $txt['moved_why'], '
								</dt>
								<dd>
									<textarea name="reason" rows="4" cols="40">', $txt['splittopic_default'], '</textarea>
								</dd>
							</dl>
						</fieldset>';

	if (!empty($context['can_move']))
		echo '
						<p>
							<label for="move_new_topic"><input type="checkbox" name="move_new_topic" id="move_new_topic" onclick="document.getElementById(\'board_list\').style.display = this.checked ? \'\' : \'none\';" class="input_check" /> ', $txt['splittopic_move'], '.</label>', template_select_boards('board_list'), '
							<script><!-- // --><![CDATA[
								document.getElementById(\'board_list\').style.display = \'none\';
							// ]]></script>
						</p>';

	echo '
						<div class="submitbutton">
							<input type="submit" value="', $txt['split_topic'], '" class="button_submit" />
							<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>';
}

/**
 * Split topics main page.
 */
function template_split_successful()
{
	global $context, $txt, $scripturl;

	echo '
	<div id="split_topics">
		<h2 class="category_header">', $txt['split_topic'], '</h2>
		<div class="windowbg">
			<div class="content">
				<p>', $txt['split_successful'], '</p>
				<ul>
					<li>
						<a href="', $scripturl, '?board=', $context['current_board'], '.0">', $txt['message_index'], '</a>
					</li>
					<li>
						<a href="', $scripturl, '?topic=', $context['old_topic'], '.0">', $txt['origin_topic'], '</a>
					</li>
					<li>
						<a href="', $scripturl, '?topic=', $context['new_topic'], '.0">', $txt['new_topic'], '</a>
					</li>
				</ul>
			</div>
		</div>
	</div>';
}

/**
 * Interface to allow selection of messages to split.
 */
function template_select()
{
	global $context, $settings, $txt, $scripturl;

	echo '
	<div id="split_topics">
		<form action="', $scripturl, '?action=splittopics;sa=splitSelection;board=', $context['current_board'], '.0" method="post" accept-charset="UTF-8">
			<div class="content">
				<div id="not_selected" class="floatleft">
					<h2 class="category_header">', $txt['split_topic'], ' - ', $txt['select_split_posts'], '</h2>
					<div class="information">
						', $txt['please_select_split'], '
					</div>', template_pagesection(false, false, array('page_index_markup' => $context['not_selected']['page_index'], 'page_index_id' => 'pageindex_not_selected')), '
					<ul id="messages_not_selected" class="split_messages smalltext">';

	foreach ($context['not_selected']['messages'] as $message)
		echo '
						<li class="windowbg', $message['alternate'] ? '2' : '', '" id="not_selected_', $message['id'], '">
							<div class="content">
								<div class="message_header">
									<a class="split_icon floatright" href="', $scripturl, '?action=splittopics;sa=selectTopics;subname=', $context['topic']['subject'], ';topic=', $context['topic']['id'], '.', $context['not_selected']['start'], ';start2=', $context['selected']['start'], ';move=down;msg=', $message['id'], '" onclick="return topicSplitselect(\'down\', ', $message['id'], ');"><img src="', $settings['images_url'], '/split_select.png" alt="-&gt;" /></a>
									<strong>', $message['subject'], '</strong> ', $txt['by'], ' <strong>', $message['poster'], '</strong><br />
									<em>', $message['time'], '</em>
								</div>
								<div class="post">', $message['body'], '</div>
							</div>
						</li>';

	echo '
						<li class="dummy"></li>
					</ul>
				</div>
				<div id="selected" class="floatright">
					<h3 class="category_header">
						', $txt['split_selected_posts'], '<a class="linkbutton floatright" href="', $scripturl, '?action=splittopics;sa=selectTopics;subname=', $context['topic']['subject'], ';topic=', $context['topic']['id'], '.', $context['not_selected']['start'], ';start2=', $context['selected']['start'], ';move=reset;msg=0" onclick="return topicSplitselect(\'reset\', 0);">', $txt['split_reset_selection'], '</a>
					</h3>
					<div class="information">
						', $txt['split_selected_posts_desc'], '
					</div>', template_pagesection(false, false, array('page_index_markup' => $context['selected']['page_index'], 'page_index_id' => 'pageindex_selected')), '
					<ul id="messages_selected" class="split_messages smalltext">';

	if (!empty($context['selected']['messages']))
	{
		foreach ($context['selected']['messages'] as $message)
			echo '
						<li class="windowbg', $message['alternate'] ? '2' : '', '" id="selected_', $message['id'], '">
							<div class="content">
								<div class="message_header">
									<a class="split_icon floatleft" href="', $scripturl, '?action=splittopics;sa=selectTopics;subname=', $context['topic']['subject'], ';topic=', $context['topic']['id'], '.', $context['not_selected']['start'], ';start2=', $context['selected']['start'], ';move=up;msg=', $message['id'], '" onclick="return topicSplitselect(\'up\', ', $message['id'], ');"><img src="', $settings['images_url'], '/split_deselect.png" alt="&lt;-" /></a>
									<strong>', $message['subject'], '</strong> ', $txt['by'], ' <strong>', $message['poster'], '</strong><br />
									<em>', $message['time'], '</em>
								</div>
								<div class="post">', $message['body'], '</div>
							</div>
						</li>';
	}

	echo '
						<li class="dummy"></li>
					</ul>
				</div>
				<div class="submitbutton clear_right">
					<input type="hidden" name="topic" value="', $context['current_topic'], '" />
					<input type="hidden" name="subname" value="', $context['new_subject'], '" />
					<input type="hidden" name="move_to_board" value="', $context['move_to_board'], '" />
					<input type="hidden" name="reason" value="', $context['reason'], '" />
					<input type="submit" value="', $txt['split_topic'], '" class="button_submit" />
					<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				</div>
			</div>
		</form>
	</div>

	<script><!-- // --><![CDATA[
		var start = [],
			topic_subject = "', $context['topic']['subject'], '",
			topic_id = "', $context['topic']['id'], '",
			not_selected_start = "', $context['not_selected']['start'], '",
			selected_start = "', $context['selected']['start'], '",
			images_url = "', $settings['images_url'], '",
			txt_by = "', $txt['by'], '";

		start[0] = ', $context['not_selected']['start'], ';
		start[1] = ', $context['selected']['start'], ';
	// ]]></script>';
}