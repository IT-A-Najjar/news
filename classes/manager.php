<?php
// This file is part of Moodle Course Rollover Plugin
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * @package     local_message
 * @author      Kristian
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_news;

use dml_exception;
use local_news\event\event_news;
use local_news\event\event_category;
use local_news\form\add;
use stdClass;

class manager
{


    public function get_num_news($userid = null)
    {
        global $DB;
        $sql = "SELECT DISTINCT * from {local_news}
                WHERE id NOT IN(SELECT DISTINCT newsid from {local_news_read}
                WHERE userid = :userid)
                ORDER BY id DESC
                LIMIT 5";
        $params = [
            'userid' => $userid,
        ];
        try {
            return $DB->get_records_sql($sql, $params);
        } catch (dml_exception $e) {
            // Log error here.
            return 0;
        }
    }

    public function get_news_filter($filter)
    {
        global $DB;
        $sql = 'SELECT ln.id, ln.newstitle, ln.newstext, ln.image, ln.categoryid, ln.timecreated
            FROM {local_news} ln
            LEFT OUTER JOIN {local_news_categories} lnc ON lnc.id = ln.categoryid
            WHERE ln.categoryid = :filterid';
        $params = [
            'filterid' => $filter
        ];
        try {
            return $DB->get_records_sql($sql, $params);
        } catch (dml_exception $e) {
            // Log error here.
            return $DB->get_records('local_news');
        }
    }

    public function read_news(int $id_news, int $id_user)
    {
        global $DB;
        $record_to_insert = new stdClass();
        $record_to_insert->newsid = $id_news;
        $record_to_insert->userid = $id_user;
        $record_to_insert->timeread = time();
        $context = \context_system::instance();
        event_news::create([

            'context' => $context,
            'other' =>
            [
                'decs' => " has view News  with id .$id_news. "


            ]

        ])->trigger();
        try {
            return $DB->insert_record('local_news_read', $record_to_insert, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    public  function get_report_news()
    {
        global $DB;
        $sql = "SELECT {local_news}.*,
                    COUNT({local_news_read}.userid) AS read_count 
                    FROM {local_news}
                    LEFT JOIN {local_news_read} ON {local_news}.id = {local_news_read}.newsid 
                    GROUP BY {local_news}.id 
                    ORDER BY read_count DESC";

        try {
            return $DB->get_records_sql($sql);
        } catch (dml_exception $e) {
            // Log error here.
            return 1;
        }
    }
    /** Insert the data into our database table.
     * @param string $message_text
     * @param string $message_type
     * @return bool true if successful
     */
    public function create_news(string $news_title, string $news_text, string $news_type, string $file): bool
    {

        $fullpath = "upload/" . time() . $file;

        global $DB, $USER;
        $context = \context_system::instance();
        $record_to_insert = new stdClass();
        $record_to_insert->newstitle = $news_title;
        $record_to_insert->newstext = $news_text;
        $record_to_insert->categoryid = $news_type;
        $record_to_insert->image = $fullpath;
        $record_to_insert->timecreated = time();
        event_news::create([

            'context' => $context,
            'other' =>
            [
                'decs' => " has create new news  with.$news_type. "


            ]
        ])->trigger();
        try {
            return $DB->insert_record('local_news', $record_to_insert, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /** Insert the data into our database table.
     * @param string $category_name
     * @param string $category_parent
     * @return bool true if successful
     */
    public function create_category(string $category_name): bool
    {
        global $DB;
        $record_to_insert = new stdClass();
        $record_to_insert->categoryname = $category_name;
        $record_to_insert->timecreated = time();
        $context = \context_system::instance();
        \local_news\event\event_category::create([

            'context' => $context,
            'other' => [
                'decs' => " create category name.$category_name."
            ]
        ])->trigger();
        try {
            return $DB->insert_record('local_news_categories', $record_to_insert, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /** Gets all messages
     * @return array of messages
     */
    public function get_all_categorys(): array
    {
        global $DB;
        $context = \context_system::instance();
        event_category::create([

            'context' => $context,
            'other' =>
            [
                'decs' => " has viewed  the list Category. "
            ]
        ])->trigger();
        return $DB->get_records('local_news_categories');
    }

    /** Get a single message from its id.
     * @param int $newsid the message we're trying to get.
     * @return object|false message data or false if not found.
     */
    public function get_news(int $newsid)
    {
        global $DB;
        return $DB->get_record('local_news', ['id' => $newsid]);
    }
    /** Get all news from its id.
     * @param int $newsid the message we're trying to get.
     * @return object|false message data or false if not found.
     */
    public function get_all_news(): array
    {
        global $DB;
        return $DB->get_records('local_news');
    }
    /** Get a single message from its id.
     * @param int $newsid the message we're trying to get.
     * @return object|false message data or false if not found.
     */
    public function get_category(int $id)
    {
        global $DB;
        return $DB->get_record('local_news_categories', ['id' => $id]);
    }
    //
    /** Update details for a single message.
     * @param int $messageid the message we're trying to get.
     * @param string $message_text the new text for the message.
     * @param string $message_type the new type for the message.
     * @return bool message data or false if not found.
     */
    public function update_news(int $newsid, string $news_title, string $news_text, string $news_type, string $file): bool
    {
        global $DB;
        $mform = new add();
        $fullpath = "upload/" . time() . $file;
        $success = $mform->save_file('image', $fullpath, true);
        if (!$success) {
            echo "Oops! something went wrong!";
        }
        $object = new stdClass();
        $object->id = $newsid;
        $object->newstitle = $news_title;
        $object->newstext = $news_text;
        $object->categoryid = $news_type;
        $object->image = $fullpath;
        $context = \context_system::instance();
        event_news::create([

            'context' => $context,
            'other' =>
            [
                'decs' => " has update new news  with id.$newsid. "
            ]
        ])->trigger();
        return $DB->update_record('local_news', $object);
    }
    //
    /** Update details for a single message.
     * @param int $messageid the message we're trying to get.
     * @param string $message_text the new text for the message.
     * @param string $message_type the new type for the message.
     * @return bool message data or false if not found.
     */
    public function update_category(int $categoryid, string $categoryname): bool
    {
        global $DB;
        $object = new stdClass();
        $object->id = $categoryid;
        $object->categoryname = $categoryname;
        $object->timecreated = time();
        $context = \context_system::instance();
        event_category::create([
            'context' => $context,
            'other' =>
            [
                'decs' => " has update category  with.$categoryid. "
            ]
        ])->trigger();
        return $DB->update_record('local_news_categories', $object);
    }
    /** Delete a message and all the read history.
     * @param $newsid
     * @return bool
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function delete_news($newsid)
    {
        global $DB;
        $news = $DB->get_record('local_news', ['id' => $newsid]);
        $filename = $news->image; // تعيين المسار الكامل للملف المراد حذفه

        $transaction = $DB->start_delegated_transaction();
        $deletedNews = $DB->delete_records('local_news', ['id' => $newsid]);
        $context = \context_system::instance();
        event_news::create([
            'context' => $context,
            'other' =>
            [
                'decs' => " delete news with id .$newsid.",
            ]
        ])->trigger();
        if (file_exists($filename)) { // التأكد من وجود الملف
            unlink($filename); // حذف الملف
            echo 'تم حذف الملف بنجاح';
        } else {
            echo 'لم يتم العثور على الملف';
        }
        if ($deletedNews) {
            $DB->commit_delegated_transaction($transaction);
        }
        return true;
    }
    /** Delete a category.
     * @param $categoryid
     * @return bool
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function delete_category($categoryid)
    {
        global $DB;
        $transaction = $DB->start_delegated_transaction();
        $deletedNews = $DB->delete_records('local_news_categories', ['id' => $categoryid]);
        if ($deletedNews) {
            $DB->commit_delegated_transaction($transaction);
        }
        $context = \context_system::instance();
        event_category::create([
            'context' => $context,
            'other' =>
            [
                'decs' => " has delete category  with.$categoryid. "
            ]
        ])->trigger();
        return true;
    }
}
