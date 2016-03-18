<?php

namespace Psr\Http\Message;

/**
 * HTTP messages consist of requests from a client to a server and responses
 * from a server to a client. This interface defines the methods common to
 * each.
 * HTTP 消息包括客户端对服务器的请求，以及服务器对客户端的响应，本接口定义了请求与响应
 * 对象通用的方法。
 *
 * Messages are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 * 在此，我们认为HTTP消息是不可变的。所有可能会改变消息状态的方法 必须（MUST） 被实现，
 * 以便保持当前HTTP消息的内部状态，并返回一个实例包含已改变的状态。
 *
 * @link http://www.ietf.org/rfc/rfc7230.txt
 * @link http://www.ietf.org/rfc/rfc7231.txt
 */
interface MessageInterface
{
    /**
     * Retrieves the HTTP protocol version as a string.
     * 以字符串形式取得HTTP协议的版本号
     *
     * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
     * 返回的字符串 必须（MUST）仅包含HTTP版本号数字（例如 “1.1”，“1.0”）
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion();

    /**
     * Return an instance with the specified HTTP protocol version.
     * 返回一个带有指定HTTP协议版本号的HTTP消息实例。
     *
     * The version string MUST contain only the HTTP version number (e.g.,
     * "1.1", "1.0").
     * 返回的字符串 必须（MUST）仅包含HTTP版本号数字（例如 “1.1”，“1.0”）
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new protocol version.
     * 本方法在子类中必须被实现用以保持HTTP消息的不变性。并且 必须（MUST）返回一个带有新
     * HTTP协议版本号的实例。
     * 
     *
     * @param string $version HTTP protocol version
     * @return self
     */
    public function withProtocolVersion($version);

    /**
     * Retrieves all message header values.
     * 取得所有的HTTP消息头的值
     *
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     * 返回值的每个元素的键代表消息头的名字，每个元素的值是由字符串组成的数组。
     *
     *     // Represent the headers as a string
     *     // 以字符串形式描述HTTP头
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     *     // Emit headers iteratively:
     *     // 迭代地列出HTTP头
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     *
     * While header names are not case-sensitive, getHeaders() will preserve the
     * exact case in which headers were originally specified.
     * 虽然消息头不区分大小写，但本方法还是会保存消息头被指定的原始值。
     *
     * @return array Returns an associative array of the message's headers. Each
     *     key MUST be a header name, and each value MUST be an array of strings
     *     for that header.
     */
    public function getHeaders();

    /**
     * Checks if a header exists by the given case-insensitive name.
     * 以不区分大小写的形式检查指定的请求头是否存在
     *
     * @param string $name Case-insensitive header field name.
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader($name);

    /**
     * Retrieves a message header value by the given case-insensitive name.
     * 根据不区分大小写的名称取出指定的HTTP消息头
     *
     * This method returns an array of all the header values of the given
     * case-insensitive header name.
     * 本方法会不区分大小写地根据名称，取出指定的值，由字符串组成的数组。
     *
     * If the header does not appear in the message, this method MUST return an
     * empty array.
     * 假如指定的消息头不存在，则 必须（MUST）返回空数组
     *
     * @param string $name Case-insensitive header field name.
     * @return string[] An array of string values as provided for the given
     *    header. If the header does not appear in the message, this method MUST
     *    return an empty array.
     */
    public function getHeader($name);

    /**
     * Retrieves a comma-separated string of the values for a single header.
     * 根据指定的HTTP消息名，获得一个消息头，消息头的值是以逗号分隔的值的字符串。
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     * 本方法不区分给定消息头名称的大小写，会返回给定消息头的值，以逗号分隔的字符串方式。
     *
     * NOTE: Not all header values may be appropriately represented using
     * comma concatenation. For such headers, use getHeader() instead
     * and supply your own delimiter when concatenating.
     * 注意：并不是所有的HTTP消息头的值都可以用逗号分隔串联，例如Cookies。对于这些消息头，
     * 应使用 getHeader() 方法替代之，并在连接的时候提供你自己的分隔符
     *
     * If the header does not appear in the message, this method MUST return
     * an empty string.
     *
     * @param string $name Case-insensitive header field name.
     * @return string A string of values as provided for the given header
     *    concatenated together using a comma. If the header does not appear in
     *    the message, this method MUST return an empty string.
     */
    public function getHeaderLine($name);

    /**
     * Return an instance with the provided value replacing the specified header.
     * 返回一个有提供的值覆盖以存在值的新实例
     *
     * While header names are case-insensitive, the casing of the header will
     * be preserved by this function, and returned from getHeaders().
     * 虽然消息头不区分大小写，但本方法还是会保存消息头被指定的原始值。
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new and/or updated header and value.
     * 本方法在子类中必须被实现用以保持HTTP消息的不变性。并且 必须（MUST）返回一个带有
     * 指定消息头（不管是添加还是被覆盖）的实例。
     *
     * @param string $name Case-insensitive header field name.
     * @param string|string[] $value Header value(s).
     * @return self
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withHeader($name, $value);

    /**
     * Return an instance with the specified header appended with the given value.
     * 返回一个附加上指定消息头名称和值的实例。
     *
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the header did not
     * exist previously, it will be added.
     * 指定HTTP头如果存在，则相应的值会保留。新值会被附加到以存在的列表中。假如指定的
     * HTTP头不存在，则会添被加。
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new header and/or value.
     * 本方法在子类中必须被实现用以保持HTTP消息的不变性。并且 必须（MUST）返回一个带有
     * 指定消息头（添加或新增）的实例。
     *
     * @param string $name Case-insensitive header field name to add.
     * @param string|string[] $value Header value(s).
     * @return self
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withAddedHeader($name, $value);

    /**
     * Return an instance without the specified header.
     * 返回一个不含有指定消息头的实例
     *
     * Header resolution MUST be done without case-sensitivity.
     * 消息头的名称 必须（MUST） 大小写不敏感
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the named header.
     * 本方法在子类中必须被实现用以保持HTTP消息的不变性。并且 必须（MUST）返回一个不含有
     * 指定消息头的实例。
     *
     * @param string $name Case-insensitive header field name to remove.
     * @return self
     */
    public function withoutHeader($name);

    /**
     * Gets the body of the message.
     * 获取消息体的实例
     *
     * @return StreamInterface Returns the body as a stream.
     */
    public function getBody();

    /**
     * Return an instance with the specified message body.
     * 返回一个带有制定消息体的实例。
     *
     * The body MUST be a StreamInterface object.
     * 消息体 必须（MUST） 是一个StreamInterface 接口的实例
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return a new instance that has the
     * new body stream.
     * 本方法在子类中必须被实现用以保持HTTP消息的不变性。并且 必须（MUST）返回一个带有新
     * 消息体的实例。
     *
     * @param StreamInterface $body Body.
     * @return self
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function withBody(StreamInterface $body);
}
